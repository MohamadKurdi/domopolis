<?php 
class ControllerSaleSupplier extends Controller {
	private $error = array();

	public function index() {
		$this->language->load('sale/supplier');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/supplier');
		$this->load->model('catalog/category');

		$this->getList();
	}

	public function field_category() {
		$this->load->model('sale/supplier');

		if (($this->request->server['REQUEST_METHOD'] == 'POST')){
			$this->model_sale_supplier->updateSupplierCategoryField($this->request->post['supplier_category_id'], $this->request->post['field'], $this->request->post['value']);
		}
	}

	public function field_attribute() {
		$this->load->model('sale/supplier');

		if (($this->request->server['REQUEST_METHOD'] == 'POST')){
			$this->model_sale_supplier->updateSupplierAttributeField($this->request->post['supplier_attribute_id'], $this->request->post['field'], $this->request->post['value']);
		}
	}

	public function clear_categories($supplier_id = null) {
		$this->load->model('sale/supplier');

		if (!$supplier_id){
			$supplier_id = $this->request->get['supplier_id'];
		}

		$supplier = $this->model_sale_supplier->getSupplier($supplier_id);		

		if ($supplier && $supplier['parser']){
			$this->supplierAdaptor->use($supplier['parser'], $supplier);
			$this->supplierAdaptor->SupplierCategory->clearCategories($supplier_id);
		}

		if (!is_cli()){
			$this->session->data['success'] = $this->language->get('text_success');

			$url .= '&supplier_id=' . $this->request->get['supplier_id'];

			$this->redirect($this->url->link('sale/supplier/update', 'token=' . $this->session->data['token'] . $url, 'SSL'));			
		}		
	}

	public function clear_attributes($supplier_id = null) {
		$this->load->model('sale/supplier');

		if (!$supplier_id){
			$supplier_id = $this->request->get['supplier_id'];
		}

		$supplier = $this->model_sale_supplier->getSupplier($supplier_id);		

		if ($supplier && $supplier['parser']){
			$this->supplierAdaptor->use($supplier['parser'], $supplier);
			$this->supplierAdaptor->SupplierAttribute->clearAttributes($supplier_id);
		}

		if (!is_cli()){
			$this->session->data['success'] = $this->language->get('text_success');

			$url .= '&supplier_id=' . $this->request->get['supplier_id'];

			$this->redirect($this->url->link('sale/supplier/update', 'token=' . $this->session->data['token'] . $url, 'SSL'));			
		}		
	}

	public function update_categories($supplier_id = null) {
		$this->load->model('sale/supplier');

		if (!$supplier_id){
			$supplier_id = $this->request->get['supplier_id'];
		}

		$supplier = $this->model_sale_supplier->getSupplier($supplier_id);		

		if ($supplier && $supplier['parser']){
			$this->supplierAdaptor->use($supplier['parser'], $supplier);
			$this->supplierAdaptor->SupplierCategory->updateCategories($this->supplierAdaptor->getCategories());
		}

		if (!is_cli()){
			$this->session->data['success'] = $this->language->get('text_success');

			$url .= '&supplier_id=' . $this->request->get['supplier_id'];

			$this->redirect($this->url->link('sale/supplier/update', 'token=' . $this->session->data['token'] . $url, 'SSL'));			
		}		
	}

	public function update_attributes($supplier_id = null) {
		$this->load->model('sale/supplier');

		if (!$supplier_id){
			$supplier_id = $this->request->get['supplier_id'];
		}

		$supplier = $this->model_sale_supplier->getSupplier($supplier_id);		

		if ($supplier && $supplier['parser']){
			$this->supplierAdaptor->use($supplier['parser'], $supplier);
			$this->supplierAdaptor->SupplierAttribute->updateAttributes($this->supplierAdaptor->getAttributes());
		}

		if (!is_cli()){
			$this->session->data['success'] = $this->language->get('text_success');

			$url .= '&supplier_id=' . $this->request->get['supplier_id'];

			$this->redirect($this->url->link('sale/supplier/update', 'token=' . $this->session->data['token'] . $url, 'SSL'));			
		}		
	}

	public function cron($supplier_id = null){
		$suppliers = $this->supplierAdaptor->getSuppliers();

		echoLine('[ControllerSaleSupplier::cron] Got ' . count($suppliers) . ' suppliers', 's');

		foreach ($suppliers as $supplier){

			if ($supplier_id){
				if ($supplier['supplier_id'] <> $supplier_id){
					echoLine('[ControllerSaleSupplier::cron] Exact supplier id provided, skipping others', 'w');
					continue;
				}
			}

			$this->supplierAdaptor->use($supplier['parser'], $supplier);
			echoLine('[ControllerSaleSupplier::cron] Working with supplier ' . $supplier['supplier_name'], 'i');

			$products = $this->supplierAdaptor->getProducts();
			echoLine('[ControllerSaleSupplier::cron] Got ' . count($products) . ' products', 'w');

			$i = 1;
			foreach ($products as $product){		
				echoLine('');
				echoLine('[ControllerSaleSupplier::cron] Now working with product ' . $i . ' of ' . count($products), 'w');			
				$this->supplierAdaptor->SupplierProduct->parseProduct($product);
				$i++;
			}

			$this->supplierAdaptor->postAction();
		}

	}

	public function insert() {
		$this->language->load('sale/supplier');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/supplier');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_sale_supplier->addSupplier($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('sale/supplier', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function update() {
		$this->language->load('sale/supplier');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/supplier');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_sale_supplier->editSupplier($this->request->get['supplier_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('sale/supplier', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->language->load('sale/supplier');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/supplier');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $supplier_id) {
				$this->model_sale_supplier->deleteSupplier($supplier_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('sale/supplier', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	public function offers(){
		$this->load->model('sale/supplier');
		$this->load->model('tool/image');

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'pao.date_added';
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

		if (isset($this->request->get['amazon_seller_id'])) {
			$amazon_seller_id = $this->request->get['amazon_seller_id'];
		} else {
			$amazon_seller_id = 0;
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['supplier_id'])) {
			$url .= '&supplier_id=' . $this->request->get['supplier_id'];
		}

		$this->data['breadcrumbs'] = array();


		$this->data['supplier'] 		= $this->model_sale_supplier->getSupplierByAmazonSellerID($amazon_seller_id);
		$this->data['heading_title'] 	= $this->language->get('heading_title');
		if ($this->data['supplier']){
			$this->data['heading_title'] = 'Офферы поставщика ' . $this->data['supplier']['supplier_name'];
			$this->document->setTitle($this->data['heading_title']);
		}

		$this->data['offers'] = [];

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * 50,
			'limit' => 50,
			'filter_amazon_seller_id' => $amazon_seller_id
		);

		$offers_total 	= $this->model_sale_supplier->getTotalSellerOffers($data);
		$offers 		= $this->model_sale_supplier->getSellerOffers($data);

		foreach ($offers as $offer){
			if ($offer['image']){
				$offer['image'] = $this->model_tool_image->resize($offer['image'], 50, 50);
			}


			$action = [];

			if ($offer['product_id']){
				$action[] = [
					'text' 		=> '<i class="fa fa-edit"></i>',
					'target' 	=> '_blank',
					'href' 		=> $this->url->link('catalog/product/update', 'token=' . $this->session->data['token'] . '&product_id=' . $offer['product_id'] . $url, 'SSL')
				];

				$action[] = [
					'text' 		=> '<i class="fa fa-eye"></i>',
					'target' 	=> '_blank',
					'href' 		=> HTTPS_CATALOG . 'index.php?route=product/product&product_id=' . $offer['product_id']
				];
			}

			$this->data['offers'][] = [
				'product_id' 			=> $offer['product_id'],
				'name' 					=> $offer['name'],
				'image' 				=> $offer['image'],

				'asin' 					=> $offer['asin'],
				'seller' 				=> $offer['sellerName'],
				'prime'	 				=> $offer['isPrime'],
				'buybox_winner'	 		=> $offer['isBuyBoxWinner'],
				'is_best'				=> $offer['isBestOffer'],
				'offer_rating'			=> $offer['offerRating'],
				'price'	 				=> $this->currency->format_with_left($offer['priceAmount'], $offer['priceCurrency'], 1),
				'delivery'	 			=> $this->currency->format_with_left($offer['deliveryAmount'], $offer['deliveryCurrency'], 1),	
				'total'					=> $this->currency->format_with_left($offer['priceAmount'] + $offer['deliveryAmount'], $offer['priceCurrency'], 1),
				'delivery_fba' 			=> $offer['deliveryIsFba'],
				'delivery_comment' 		=> $offer['deliveryComments'],
				'min_days' 				=> $offer['minDays'],
				'delivery_from' 		=> $offer['deliveryFrom'],
				'delivery_to' 			=> $offer['deliveryTo'],
				'is_min_price'			=> $offer['is_min_price'],							
				'reviews'				=> (int)$offer['sellerRatingsTotal'],
				'rating'				=> (int)$offer['sellerRating50'] / 10,
				'positive'				=> (int)$offer['sellerPositiveRatings100'],
				'quality'				=> $offer['sellerQuality'],
				'country'				=> $offer['offerCountry'],
				'is_native'				=> $offer['isNativeOffer'],
				'date_added'			=> date('Y-m-d H:i:s', strtotime($offer['date_added'])),
				'link'					=> $offer['sellerLink']?$offer['sellerLink']:$this->rainforestAmazon->createLinkToAmazonSearchPage($offer['asin']),
				'link2'					=> $this->rainforestAmazon->createLinkToAmazonSearchPage($offer['asin']),
				'action' 				=> $action
			];

		}
		
		$this->data['text_no_results'] 		= $this->language->get('text_no_results');
		$this->data['column_name'] 			= $this->language->get('column_name');
		$this->data['column_code'] 			= $this->language->get('column_code');
		$this->data['column_sort_order'] 	= $this->language->get('column_sort_order');
		$this->data['column_action'] 		= $this->language->get('column_action');

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

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->data['sort_name'] = $this->url->link('sale/supplier', 'token=' . $this->session->data['token'] . '&sort=name' . $url);
		$this->data['sort_code'] = $this->url->link('sale/supplier', 'token=' . $this->session->data['token'] . '&sort=code' . $url);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination 		= new Pagination();
		$pagination->total 	= $offers_total;
		$pagination->page 	= $page;
		$pagination->limit 	= 50;
		$pagination->text 	= $this->language->get('text_pagination');
		$pagination->url 	= $this->url->link('sale/supplier/offers', 'token=' . $this->session->data['token'] . $url . '&page={page}');

		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] 	= $sort;
		$this->data['order'] 	= $order;
		$this->data['amazon_seller_id'] 	= $amazon_seller_id;

		$this->template = 'sale/supplier_offers.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}	

	protected function getList() {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'sort_order, supplier_name';
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

		$filters = [
			'filter_supplier_name' 		=> null,
			'filter_supplier_country'	=> null,
			'filter_rating_from' 		=> null,
			'filter_reviews_from' 		=> null,
			'filter_has_telephone' 		=> null,
			'filter_has_email' 			=> null,
			'filter_has_vat' 			=> null,
		];

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

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		foreach ($filters as $filter => $default_value){
			if (isset($this->request->get[$filter])) {
				$url .= '&' . $filter . '=' . urlencode(html_entity_decode($this->request->get[$filter], ENT_QUOTES, 'UTF-8'));
			}
		}

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token']),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('sale/supplier', 'token=' . $this->session->data['token'] . $url),
			'separator' => ' :: '
		);

		$this->data['insert'] = $this->url->link('sale/supplier/insert', 'token=' . $this->session->data['token'] . $url);
		$this->data['delete'] = $this->url->link('sale/supplier/delete', 'token=' . $this->session->data['token'] . $url);

		$this->data['suppliers'] = [];

		$data['sort'] 	= $sort;
		$data['order'] 	= $order;
		$data['start'] 	= ($page - 1) * 50;
		$data['limit'] 	= 50;

		$supplier_total = $this->model_sale_supplier->getTotalSuppliers($data);
		$results 		= $this->model_sale_supplier->getSuppliers($data);

		foreach ($results as $result) {
			$child_filter_data = array(
				'sort'  => $sort,
				'order' => $order,
				'start' => ($page - 1) * 50,
				'limit' => 50,
				'filter_parent_id' => $result['supplier_id']
			);

			$children = [];
			$children_results = $this->model_sale_supplier->getSuppliers($child_filter_data);

			foreach ($children_results as $children_result) {
				$action = array();

				$action[] = array(
					'text' => 'Изменить',
					'href' => $this->url->link('sale/supplier/update', 'token=' . $this->session->data['token'] . '&supplier_id=' . $children_result['supplier_id'] . $url, 'SSL')
				);

				if ($children_result['supplier_parent_id']){
					$_sp = $this->model_sale_supplier->getSupplier($children_result['supplier_parent_id']);
					$_sp = $_sp['supplier_name'];
					$_hassp = true;
				} else {
					$_sp = 'Головной поставщик';
					$_hassp = false;
				}

				$children[] = array(
					'supplier_id' 		   	=> $children_result['supplier_id'],
					'supplier_name'        	=> $children_result['supplier_name'],
					'supplier_code'        	=> $children_result['supplier_code'],
					'supplier_type'        	=> $children_result['supplier_type'],
					'supplier_parent'      	=> $_sp,
					'supplier_hassp'       	=> $_hassp,
					'supplier_country'     	=> $children_result['supplier_country'],
					'supplier_inner'       	=> $children_result['supplier_inner'],
					'supplier_comment'     	=> $children_result['supplier_comment'],
					'supplier_m_coef'     	=> $children_result['supplier_m_coef'],
					'supplier_l_coef'     	=> $children_result['supplier_l_coef'],
					'supplier_n_coef'     	=> $children_result['supplier_n_coef'],
					'terms_instock'    	 	=> $children_result['terms_instock'],
					'terms_outstock'     	=> $children_result['terms_outstock'],
					'amzn_good'     		=> $children_result['amzn_good'],
					'amzn_bad'     			=> $children_result['amzn_bad'],
					'store_link'     		=> $children_result['store_link'],
					'amazon_seller_id'     	=> $children_result['amazon_seller_id'],
					'vat_number'     		=> $children_result['vat_number'],
					'business_type'     	=> $children_result['business_type'],
					'email'     			=> $children_result['email'],
					'telephone'     		=> $children_result['telephone'],
					'sort_order'  		  	=> $children_result['sort_order'],								
					'selected'            	=> isset($this->request->post['selected']) && in_array($children_result['supplier_id'], $this->request->post['selected']),
					'action'              	=> $action	
				);		
			}


			$action = [];

			$action[] = [
				'text' => '<i class="fa fa-edit"></i>',
				'href' => $this->url->link('sale/supplier/update', 'token=' . $this->session->data['token'] . '&supplier_id=' . $result['supplier_id'] . $url, 'SSL')
			];

			$action[] = [
				'text' => '<i class="fa fa-eye"></i>',
				'href' => $this->url->link('sale/supplier/offers', 'token=' . $this->session->data['token'] . '&amazon_seller_id=' . $result['amazon_seller_id'] . $url, 'SSL')
			];

			if ($result['store_link']){
				$action[] = [
					'text' 		=> '<i class="fa fa-amazon"></i>',
					'target' 	=> '_blank',
					'href' 		=> $result['store_link']
				];
			}

			if ($result['supplier_parent_id']){
				$_sp = $this->model_sale_supplier->getSupplier($result['supplier_parent_id']);
				$_sp = $_sp['supplier_name'];
				$_hassp = true;
			} else {
				$_sp = 'Основной';
				$_hassp = false;
			}

			if (!empty($result['amazon_seller_id'])){
				$total_offers = $this->model_sale_supplier->getTotalSellerOffers(['filter_amazon_seller_id' => $result['amazon_seller_id']]);
			} else {
				$total_offers = 0;
			}

			$this->data['suppliers'][] = [
				'supplier_id' 		   => $result['supplier_id'],
				'supplier_name'        => $result['supplier_name'],
				'supplier_code'        => $result['supplier_code'],
				'supplier_type'        => $result['supplier_type'],
				'supplier_parent'      => $_sp,
				'supplier_hassp'       => $_hassp,
				'supplier_country'     => $result['supplier_country'],
				'supplier_inner'       => $result['supplier_inner'],
				'supplier_comment'     => $result['supplier_comment'],
				'supplier_m_coef'     	=> $result['supplier_m_coef'],
				'supplier_l_coef'     	=> $result['supplier_l_coef'],
				'supplier_n_coef'     	=> $result['supplier_n_coef'],
				'terms_instock'     	=> $result['terms_instock'],
				'terms_outstock'     	=> $result['terms_outstock'],
				'amzn_good'     		=> $result['amzn_good'],
				'amzn_bad'     			=> $result['amzn_bad'],
				'amzn_coefficient' 		=> $result['amzn_coefficient'],
				'store_link'     		=> $result['store_link'],
				'amazon_seller_id'     	=> $result['amazon_seller_id'],
				'vat_number'     		=> $result['vat_number'],
				'business_name'     	=> $result['business_name'],
				'business_type'     	=> $result['business_type'],
				'path_to_feed'     		=> $result['path_to_feed'],
				'stock'     			=> $result['stock'],
				'prices'     			=> $result['prices'],
				'auto_enable'     		=> $result['auto_enable'],
				'skip_no_category'     	=> $result['skip_no_category'],
				'same_as_warehouse'     => $result['same_as_warehouse'],
				'parser'     			=> $result['parser'],
				'parser_status'     	=> $result['parser_status'],
				'rrp_in_feed'     		=> $result['rrp_in_feed'],
				'total_offers'     		=> $total_offers,
				'email'     			=> $result['email'],
				'telephone'     		=> $result['telephone'],
				'is_native'     		=> $result['is_native'],
				'reviews'				=> (int)$result['ratings_total'],
				'rating'				=> (int)$result['rating50'] / 10,
				'positive'				=> (int)$result['positive_ratings100'],
				'sort_order'  		  => $result['sort_order'],
				'children'		      => $children,
				'selected'            => isset($this->request->post['selected']) && in_array($result['supplier_id'], $this->request->post['selected']),
				'action'              => $action	
			];		
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_code'] = $this->language->get('column_code');
		$this->data['column_sort_order'] = $this->language->get('column_sort_order');
		$this->data['column_action'] = $this->language->get('column_action');

		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');

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

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		foreach ($filters as $filter => $default_value){
			if (isset($this->request->get[$filter])) {
				$url .= '&' . $filter . '=' . urlencode(html_entity_decode($this->request->get[$filter], ENT_QUOTES, 'UTF-8'));
			}
		}

		$this->data['sort_name'] = $this->url->link('sale/supplier', 'token=' . $this->session->data['token'] . '&sort=name' . $url);
		$this->data['sort_code'] = $this->url->link('sale/supplier', 'token=' . $this->session->data['token'] . '&sort=code' . $url);
		$this->data['sort_sort_order'] = $this->url->link('sale/supplier', 'token=' . $this->session->data['token'] . '&sort=sort_order' . $url);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		foreach ($filters as $filter => $default_value){
			if (isset($this->request->get[$filter])) {
				$url .= '&' . $filter . '=' . urlencode(html_entity_decode($this->request->get[$filter], ENT_QUOTES, 'UTF-8'));
			}
		}

		$pagination 		= new Pagination($this->registry, [
			'total' => $supplier_total,
			'page' 	=> $page,
			'limit' => 50,
			'url'  	=> $this->url->link('sale/supplier', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL')
		]);

		$this->data['pagination'] = $pagination->render();

		$this->data['token'] = $this->session->data['token'];

		$this->data['sort'] 				= $sort;
		$this->data['order'] 				= $order;

		$this->template = 'sale/supplier_list.tpl';
		$this->children = [
			'common/header',
			'common/footer'
		];

		$this->response->setOutput($this->render());
	}

	protected function getForm() {
		$this->load->model('sale/supplier');
		$this->load->model('catalog/category');
		$this->load->model('localisation/language');        
		$this->load->model('catalog/attribute');  

        $this->data['languages'] = $this->model_localisation_language->getLanguages();

		$this->data['heading_title'] 	= $this->language->get('heading_title');
		$this->data['text_enabled'] 	= $this->language->get('text_enabled');
		$this->data['text_disabled'] 	= $this->language->get('text_disabled');
		$this->data['entry_name'] 		= $this->language->get('entry_name');
		$this->data['entry_code'] 		= $this->language->get('entry_code');
		$this->data['entry_locale'] 	= $this->language->get('entry_locale');
		$this->data['entry_image'] 		= $this->language->get('entry_image');
		$this->data['entry_directory'] 	= $this->language->get('entry_directory');
		$this->data['entry_filename'] 	= $this->language->get('entry_filename');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['entry_status'] 	= $this->language->get('entry_status');
		$this->data['button_save'] 		= $this->language->get('button_save');
		$this->data['button_cancel'] 	= $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$this->data['error_name'] = $this->error['name'];
		} else {
			$this->data['error_name'] = '';
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
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
			'href'      => $this->url->link('sale/supplier', 'token=' . $this->session->data['token'] . $url),      		
			'separator' => ' :: '
		);

		if (!isset($this->request->get['supplier_id'])) {
			$this->data['action'] = $this->url->link('sale/supplier/insert', 'token=' . $this->session->data['token'] . $url);
		} else {
			$this->data['action'] = $this->url->link('sale/supplier/update', 'token=' . $this->session->data['token'] . '&supplier_id=' . $this->request->get['supplier_id'] . $url);
		}

		$this->data['cancel'] = $this->url->link('sale/supplier', 'token=' . $this->session->data['token'] . $url);

		if (isset($this->request->get['supplier_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$supplier_info = $this->model_sale_supplier->getSupplier($this->request->get['supplier_id']);
		}

		if (isset($this->request->post['supplier_name'])) {
			$this->data['supplier_name'] = $this->request->post['supplier_name'];
		} elseif (!empty($supplier_info)) {
			$this->data['supplier_name'] = $supplier_info['supplier_name'];
		} else {
			$this->data['supplier_name'] = '';
		}

		if (isset($this->request->post['supplier_code'])) {
			$this->data['supplier_code'] = $this->request->post['supplier_code'];
		} elseif (!empty($supplier_info)) {
			$this->data['supplier_code'] = $supplier_info['supplier_code'];
		} else {
			$this->data['supplier_code'] = '';
		}

		if (isset($this->request->post['supplier_country'])) {
			$this->data['supplier_country'] = $this->request->post['supplier_country'];
		} elseif (!empty($supplier_info)) {
			$this->data['supplier_country'] = $supplier_info['supplier_country'];
		} else {
			$this->data['supplier_country'] = '';
		}

		if (isset($this->request->post['supplier_type'])) {
			$this->data['supplier_type'] = $this->request->post['supplier_type'];
		} elseif (!empty($supplier_info)) {
			$this->data['supplier_type'] = $supplier_info['supplier_type'];
		} else {
			$this->data['supplier_type'] = '';
		}

		if (isset($this->request->post['supplier_parent_id'])) {
			$this->data['supplier_parent_id'] = $this->request->post['supplier_parent_id'];
		} elseif (!empty($supplier_info)) {
			$this->data['supplier_parent_id'] = $supplier_info['supplier_parent_id'];
		} else {
			$this->data['supplier_parent_id'] = 0;
		}

		if ($this->data['supplier_parent_id']){
			$this->data['supplier_parent'] = $this->model_sale_supplier->getSupplier($this->data['supplier_parent_id']);
			$this->data['supplier_parent'] = $this->data['supplier_parent']['supplier_name'];
		} else {
			$this->data['supplier_parent'] = '';
		}

		if (isset($this->request->post['supplier_inner'])) {
			$this->data['supplier_inner'] = $this->request->post['supplier_inner'];
		} elseif (!empty($supplier_info)) {
			$this->data['supplier_inner'] = $supplier_info['supplier_inner'];
		} else {
			$this->data['supplier_inner'] = 0;
		}

		if (isset($this->request->post['supplier_comment'])) {
			$this->data['supplier_comment'] = $this->request->post['supplier_comment'];
		} elseif (!empty($supplier_info)) {
			$this->data['supplier_comment'] = $supplier_info['supplier_comment'];
		} else {
			$this->data['supplier_comment'] = '';
		}

		if (isset($this->request->post['supplier_m_coef'])) {
			$this->data['supplier_m_coef'] = $this->request->post['supplier_m_coef'];
		} elseif (!empty($supplier_info)) {
			$this->data['supplier_m_coef'] = $supplier_info['supplier_m_coef'];
		} else {
			$this->data['supplier_m_coef'] = 0;
		}

		if (isset($this->request->post['supplier_l_coef'])) {
			$this->data['supplier_l_coef'] = $this->request->post['supplier_l_coef'];
		} elseif (!empty($supplier_info)) {
			$this->data['supplier_l_coef'] = $supplier_info['supplier_l_coef'];
		} else {
			$this->data['supplier_l_coef'] = 0;
		}

		if (isset($this->request->post['supplier_n_coef'])) {
			$this->data['supplier_n_coef'] = $this->request->post['supplier_n_coef'];
		} elseif (!empty($supplier_info)) {
			$this->data['supplier_n_coef'] = $supplier_info['supplier_n_coef'];
		} else {
			$this->data['supplier_n_coef'] = 0;
		}

		if (isset($this->request->post['terms_instock'])) {
			$this->data['terms_instock'] = $this->request->post['terms_instock'];
		} elseif (!empty($supplier_info)) {
			$this->data['terms_instock'] = $supplier_info['terms_instock'];
		} else {
			$this->data['terms_instock'] = 0;
		}

		if (isset($this->request->post['terms_outstock'])) {
			$this->data['terms_outstock'] = $this->request->post['terms_outstock'];
		} elseif (!empty($supplier_info)) {
			$this->data['terms_outstock'] = $supplier_info['terms_outstock'];
		} else {
			$this->data['terms_outstock'] = 0;
		}

		if (isset($this->request->post['amzn_good'])) {
			$this->data['amzn_good'] = $this->request->post['amzn_good'];
		} elseif (!empty($supplier_info)) {
			$this->data['amzn_good'] = $supplier_info['amzn_good'];
		} else {
			$this->data['amzn_good'] = 0;
		}

		if (isset($this->request->post['amzn_bad'])) {
			$this->data['amzn_bad'] = $this->request->post['amzn_bad'];
		} elseif (!empty($supplier_info)) {
			$this->data['amzn_bad'] = $supplier_info['amzn_bad'];
		} else {
			$this->data['amzn_bad'] = 0;
		}

		if (isset($this->request->post['amzn_coefficient'])) {
			$this->data['amzn_coefficient'] = $this->request->post['amzn_coefficient'];
		} elseif (!empty($supplier_info)) {
			$this->data['amzn_coefficient'] = $supplier_info['amzn_coefficient'];
		} else {
			$this->data['amzn_coefficient'] = 0;
		}

		if (isset($this->request->post['is_native'])) {
			$this->data['is_native'] = $this->request->post['is_native'];
		} elseif (!empty($supplier_info)) {
			$this->data['is_native'] = $supplier_info['is_native'];
		} else {
			$this->data['is_native'] = 0;
		}

		if (isset($this->request->post['path_to_feed'])) {
			$this->data['path_to_feed'] = $this->request->post['path_to_feed'];
		} elseif (!empty($supplier_info)) {
			$this->data['path_to_feed'] = $supplier_info['path_to_feed'];
		} else {
			$this->data['path_to_feed'] = '';
		}

		$this->data['parser_libraries'] = $this->supplierAdaptor->getSupplierLibraries();

		if (isset($this->request->post['parser'])) {
			$this->data['parser'] = $this->request->post['parser'];
		} elseif (!empty($supplier_info)) {
			$this->data['parser'] = $supplier_info['parser'];
		} else {
			$this->data['parser'] = 0;
		}

		if (isset($this->request->post['parser_status'])) {
			$this->data['parser_status'] = $this->request->post['parser_status'];
		} elseif (!empty($supplier_info)) {
			$this->data['parser_status'] = $supplier_info['parser_status'];
		} else {
			$this->data['parser_status'] = 0;
		}

		if (isset($this->request->post['rrp_in_feed'])) {
			$this->data['rrp_in_feed'] = $this->request->post['rrp_in_feed'];
		} elseif (!empty($supplier_info)) {
			$this->data['rrp_in_feed'] = $supplier_info['rrp_in_feed'];
		} else {
			$this->data['rrp_in_feed'] = 0;
		}

		if (isset($this->request->post['language_in_feed'])) {
			$this->data['language_in_feed'] = $this->request->post['language_in_feed'];
		} elseif (!empty($supplier_info)) {
			$this->data['language_in_feed'] = $supplier_info['language_in_feed'];
		} else {
			$this->data['language_in_feed'] = '';
		}

		if (isset($this->request->post['sync_field'])) {
			$this->data['sync_field'] = $this->request->post['sync_field'];
		} elseif (!empty($supplier_info)) {
			$this->data['sync_field'] = $supplier_info['sync_field'];
		} else {
			$this->data['sync_field'] = 'sku';
		}

		if (isset($this->request->post['auto_enable'])) {
			$this->data['auto_enable'] = $this->request->post['auto_enable'];
		} elseif (!empty($supplier_info)) {
			$this->data['auto_enable'] = $supplier_info['auto_enable'];
		} else {
			$this->data['auto_enable'] = 0;
		}

		if (isset($this->request->post['skip_no_category'])) {
			$this->data['skip_no_category'] = $this->request->post['skip_no_category'];
		} elseif (!empty($supplier_info)) {
			$this->data['skip_no_category'] = $supplier_info['skip_no_category'];
		} else {
			$this->data['skip_no_category'] = 0;
		}
		
		if (isset($this->request->post['same_as_warehouse'])) {
			$this->data['same_as_warehouse'] = $this->request->post['same_as_warehouse'];
		} elseif (!empty($supplier_info)) {
			$this->data['same_as_warehouse'] = $supplier_info['same_as_warehouse'];
		} else {
			$this->data['same_as_warehouse'] = 0;
		}

		if (isset($this->request->post['stock'])) {
			$this->data['stock'] = $this->request->post['stock'];
		} elseif (!empty($supplier_info)) {
			$this->data['stock'] = $supplier_info['stock'];
		} else {
			$this->data['stock'] = 0;
		}

		if (isset($this->request->post['prices'])) {
			$this->data['prices'] = $this->request->post['prices'];
		} elseif (!empty($supplier_info)) {
			$this->data['prices'] = $supplier_info['prices'];
		} else {
			$this->data['prices'] = 0;
		}

		if (isset($this->request->post['currency'])) {
			$this->data['currency'] = $this->request->post['currency'];
		} elseif (!empty($supplier_info)) {
			$this->data['currency'] = $supplier_info['currency'];
		} else {
			$this->data['currency'] = '';
		}

		$this->data['supplier_categories'] 			= [];
		$this->data['supplier_categories_total'] 	= 0;
		if (!empty($supplier_info)){
			$this->data['supplier_categories_total'] = $this->model_sale_supplier->getTotalSupplierCategories($supplier_info['supplier_id']);
			$supplier_categories = $this->model_sale_supplier->getSupplierCategories($supplier_info['supplier_id']);

			foreach ($supplier_categories as $supplier_category){
				if ($supplier_category['category_id']){
					$category = $this->model_catalog_category->getCategory($supplier_category['category_id']);
					$path     = ($category['path'] ? $category['path'] . ' &gt; ' : '') . $category['name'];			
				} else {
					$category 	= false;
					$path 		= false;					
				}

				$guessed_data = [];
				$guessed =  $this->model_sale_supplier->tryToGuessCategory($supplier_category['supplier_category']);

				foreach ($guessed as $guesse){
					$guessed_data[] = [
						'category_id' => $guesse['category_id'], 
						'name'        => strip_tags(html_entity_decode($guesse['name'], ENT_QUOTES, 'UTF-8'))
					];
				}

				$sort_order = [];

				foreach ($guessed_data as $key => $value) {
					$sort_order[$key] = $value['name'];
				}

				array_multisort($sort_order, SORT_ASC, $guessed_data);

				$this->data['supplier_categories'][] = [
					'supplier_category_id' 		=> $supplier_category['supplier_category_id'],
					'supplier_category' 		=> $supplier_category['supplier_category'],
					'supplier_category_full' 	=> $supplier_category['supplier_category_full'],
					'category_id' 				=> $supplier_category['category_id'],
					'products' 					=> $supplier_category['products'],
					'stocks' 					=> $supplier_category['stocks'],
					'prices' 					=> $supplier_category['prices'],
					'category' 					=> $category,
					'path' 						=> $path,
					'guessed' 					=> $guessed_data
				];
			}
		}

		$this->data['supplier_attributes'] 			= [];
		$this->data['supplier_attributes_total'] 	= 0;
		if (!empty($supplier_info)){
			$this->data['supplier_attributes_total'] = $this->model_sale_supplier->getTotalSupplierAttributes($supplier_info['supplier_id']);
			$supplier_attributes = $this->model_sale_supplier->getSupplierAttributes($supplier_info['supplier_id']);

			foreach ($supplier_attributes as $supplier_attribute){
				if ($supplier_attribute['attribute_id'] && $attribute = $this->model_catalog_attribute->getAttribute($supplier_attribute['attribute_id'])){					
					$name      = $attribute['name'];			
				} else {
					$attribute 	= false;
					$name 		= false;					
				}

				$guessed_data = [];
				$guessed =  $this->model_sale_supplier->tryToGuessAttribute($supplier_attribute['supplier_attribute']);

				foreach ($guessed as $guesse){
					$guessed_data[] = [
						'attribute_id' 	=> $guesse['attribute_id'], 
						'name'        	=> strip_tags(html_entity_decode($guesse['name'], ENT_QUOTES, 'UTF-8'))
					];
				}

				$sort_order = [];

				foreach ($guessed_data as $key => $value) {
					$sort_order[$key] = $value['name'];
				}

				array_multisort($sort_order, SORT_ASC, $guessed_data);

				$this->data['supplier_attributes'][] = [
					'supplier_attribute_id' 	=> $supplier_attribute['supplier_attribute_id'],
					'supplier_attribute' 		=> $supplier_attribute['supplier_attribute'],
					'attribute_id' 				=> $supplier_attribute['attribute_id'],
					'name' 						=> $name,
					'guessed' 					=> $guessed_data
				];
			}
		}
		

		$amazon_keys_char = [
			'amazon_seller_id',
			'store_link',
			'business_name',
			'registration_number',
			'vat_number',
			'business_type',
			'about_this_seller',
			'detailed_information',
			'telephone',
			'email'
		];

		foreach ($amazon_keys_char as $amazon_key_char){
			if (isset($this->request->post[$amazon_key_char])) {
				$this->data[$amazon_key_char] = $this->request->post[$amazon_key_char];
			} elseif (!empty($supplier_info)) {
				$this->data[$amazon_key_char] = $supplier_info[$amazon_key_char];
			} else {
				$this->data[$amazon_key_char] = '';
			}
		}

		$amazon_keys_int = [
			'rating50',
			'ratings_total',
			'positive_ratings100'
		];

		foreach ($amazon_keys_int as $amazon_key_int){
			if (isset($this->request->post[$amazon_key_int])) {
				$this->data[$amazon_key_int] = $this->request->post[$amazon_key_int];
			} elseif (!empty($supplier_info)) {
				$this->data[$amazon_key_int] = $supplier_info[$amazon_key_int];
			} else {
				$this->data[$amazon_key_int] = 0;
			}
		}

		if (isset($this->request->post['sort_order'])) {
			$this->data['sort_order'] = $this->request->post['sort_order'];
		} elseif (!empty($supplier_info)) {
			$this->data['sort_order'] = $supplier_info['sort_order'];
		} else {
			$this->data['sort_order'] = 0;
		}

		if (!empty($supplier_info) && !empty($supplier_info['amazon_seller_id'])) {
			$this->data['offers_link'] = $this->url->link('sale/supplier/offers', 'token=' . $this->session->data['token'] . '&amazon_seller_id=' . $supplier_info['amazon_seller_id']);			
		}

		if (!empty($supplier_info)){
			$this->data['update_categories'] = $this->url->link('sale/supplier/update_categories', 'token=' . $this->session->data['token'] . '&supplier_id=' . $supplier_info['supplier_id']);
			$this->data['clear_categories'] = $this->url->link('sale/supplier/clear_categories', 'token=' . $this->session->data['token'] . '&supplier_id=' . $supplier_info['supplier_id']);

			$this->data['update_attributes'] = $this->url->link('sale/supplier/update_attributes', 'token=' . $this->session->data['token'] . '&supplier_id=' . $supplier_info['supplier_id']);
			$this->data['clear_attributes'] = $this->url->link('sale/supplier/clear_attributes', 'token=' . $this->session->data['token'] . '&supplier_id=' . $supplier_info['supplier_id']);
		}

		$this->data['token'] = $this->session->data['token'];


		$this->template = 'sale/supplier_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'sale/supplier')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['supplier_name']) < 2) || (utf8_strlen($this->request->post['supplier_name']) > 255)) {
			$this->error['supplier_name'] = $this->language->get('error_name');
		}


		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'sale/supplier')) {
			$this->error['warning'] = $this->language->get('error_permission');
		} 

		$this->load->model('setting/store');
		$this->load->model('sale/order');

		foreach ($this->request->post['selected'] as $supplier_id) {

		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_name'])) {
			$this->load->model('sale/supplier');

			$data = array(
				'filter_name' => $this->request->get['filter_name'],
				'start'       => 0,
				'limit'       => 50
			);

			$results = $this->model_sale_supplier->getSuppliers($data);

			foreach ($results as $result) {
				$json[] = array(
					'supplier_id' => $result['supplier_id'], 
					'supplier_name'        => strip_tags(html_entity_decode($result['supplier_name']. ' : '.$result['supplier_type'], ENT_QUOTES, 'UTF-8'))
				);
			}		
		}

		$sort_order = array();

		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['supplier_name'];
		}

		array_multisort($sort_order, SORT_ASC, $json);

		$this->response->setOutput(json_encode($json));
	}			
}	
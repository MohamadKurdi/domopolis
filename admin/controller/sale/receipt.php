<?php
class ControllerSaleReceipt extends Controller {
	private $error = array();
	private $checkbox_api;
	private $setting;
	private $limit = 5;

	public function __construct($registry){
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);

		parent::__construct($registry);
		$this->load->model('sale/receipt');
		$this->load->language('sale/receipt');
		$this->init();
	}

	public function index() {
		$this->document->setTitle($this->language->get('heading_title'));
		$this->getList();
	}

	public function preview() {
		$this->document->setTitle($this->language->get('heading_title_preview'));
		$this->getPreviewForm();
	}

	public function delete() {
		$this->document->setTitle($this->language->get('heading_title'));

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $order_id) {
				$this->model_sale_receipt->deleteReceipt($order_id);
			}

			$this->session->data['success'] = $this->language->get('text_success_delete');
			$url = '';
			if (isset($this->request->get['filter_fiscal_code'])) {
				$url .= '&filter_fiscal_code=' . $this->request->get['filter_fiscal_code'];
			}
			if (isset($this->request->get['filter_order_id'])) {
				$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
			}
			if (isset($this->request->get['filter_customer'])) {
				$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
			}
			if (isset($this->request->get['filter_product'])) {
				$url .= '&filter_product=' . urlencode(html_entity_decode($this->request->get['filter_product'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
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

			$this->redirect($this->url->link('sale/receipt', 'token=' . $this->session->data['token'] . $url, true));
		}
		$this->getList();
	}

	public function updateReceiptInfo(){
        $json = array();
        if(isset($this->request->get['receipt_id'])){
            $receipt_id = $this->request->get['receipt_id'];
            $json = $this->checkBoxUA->getReceipt($receipt_id);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

	protected function getList() {
		if (isset($this->request->get['filter_fiscal_code'])) {
			$filter_fiscal_code = $this->request->get['filter_fiscal_code'];
		} else {
			$filter_fiscal_code = null;
		}

		if (isset($this->request->get['filter_order_id'])) {
			$filter_order_id = $this->request->get['filter_order_id'];
		} else {
			$filter_order_id = null;
		}

		if (isset($this->request->get['filter_customer'])) {
			$filter_customer = $this->request->get['filter_customer'];
		} else {
			$filter_customer = null;
		}

		if (isset($this->request->get['filter_order_status'])) {
			$filter_order_status = $this->request->get['filter_order_status'];
		} else {
			$filter_order_status = null;
		}
		
		if (isset($this->request->get['filter_order_payment_code'])) { 
			$filter_order_payment_code = $this->request->get['filter_order_payment_code'];
		} else {
			$filter_order_payment_code = null;
		}

		if (isset($this->request->get['filter_has_receipt'])) {
			$filter_has_receipt = $this->request->get['filter_has_receipt'];
		} else {
			$filter_has_receipt = null;
		}


		if (isset($this->request->get['filter_date_added'])) {
			$filter_date_added = $this->request->get['filter_date_added'];
		} else {
			$filter_date_added = null;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'r.return_id';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		$receipt_limit = $this->config->get('receipt_limit') ? $this->config->get('receipt_limit') : $this->limit;

		$url = '';

		if (isset($this->request->get['filter_fiscal_code'])) {
			$url .= '&filter_fiscal_code=' . $this->request->get['filter_fiscal_code'];
		}

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_order_status'])) {
			$url .= '&filter_order_status=' . $this->request->get['filter_order_status'];
		}
		if (isset($this->request->get['filter_order_payment_code'])) {
			$url .= '&filter_order_payment_code=' . $this->request->get['filter_order_payment_code'];
		}
		if (isset($this->request->get['filter_has_receipt'])) {
			$url .= '&filter_has_receipt=' . $this->request->get['filter_has_receipt'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
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

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('sale/receipt', 'token=' . $this->session->data['token'] . $url, true)
		);

		$data['action'] = $this->url->link('sale/receipt', 'token=' . $this->session->data['token'] . $url, true);
		$data['delete'] = $this->url->link('sale/receipt/delete', 'token=' . $this->session->data['token'] . $url, true);
        $data['create_orders'] = $this->url->link('sale/receipt/createSelected', 'token=' . $this->session->data['token'] , true);
        $data['receipt_log'] = $this->url->link('tool/receipt_log', 'token=' . $this->session->data['token'] , true);

		$data['receipts'] = array();

		$filter_data = array(
			'filter_fiscal_code'        	=> $filter_fiscal_code,
			'filter_order_id'         		=> $filter_order_id,
			'filter_customer'         		=> $filter_customer,
			'filter_has_receipt'          	=> $filter_has_receipt,
			'filter_date_added'       		=> $filter_date_added,
			'filter_order_status'  			=> $filter_order_status,
			'filter_order_payment_code'  	=> $filter_order_payment_code,
			'sort'                    		=> $sort,
			'order'                   		=> $order,
			'start'                   		=> ($page - 1) * $receipt_limit,
			'limit'                   		=> $receipt_limit
		);

		$receipt_total 	= $this->model_sale_receipt->getTotalOrders($filter_data);
		$results 		= $this->model_sale_receipt->getOrders($filter_data);

		foreach ($results as $result) {

			$data['receipts'][] = array( 
				'order_id'      		=> $result['order_id'],
				'order_info_link'      	=> $this->url->link('sale/order/info', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'] . $url, true),
				
				'fiscal_code'      		=> $result['fiscal_code'],
				'shipping_method'      	=> $result['shipping_method'],
				'payment_method'      	=> $result['payment_method'],
				'payment_code'      	=> $result['payment_code'],
				'receipt_id'      		=> $result['receipt_id'],  
				'preview_data'      	=> (!$result['receipt_id']) ? $this->getPreviewData($result['order_id']) : array(),

				'update_link'      	=> $this->url->link('sale/receipt/updateReceiptInfo', 'token=' . $this->session->data['token'] . '&receipt_id=' . $result['receipt_id'] . $url, true),
				'html_link'      	=> $this->checkBoxUA->getReceiptLink($result['receipt_id'],'html'),
				'pdf_link'      	=> $this->checkBoxUA->getReceiptLink($result['receipt_id'],'pdf'),
				'text_link'      	=> $this->checkBoxUA->getReceiptLink($result['receipt_id'],'text'),
				'png_link'      	=> $this->checkBoxUA->getReceiptLink($result['receipt_id'],'png'),
				'qrcode_link'      	=> $this->checkBoxUA->getReceiptLink($result['receipt_id'],'qrcode'),

				'is_sent_dps'      		=> $result['is_sent_dps'], 
				'sent_dps_at'      		=> $result['sent_dps_at'], 
				'is_created_offline' 	=> $result['is_created_offline'] ? $this->language->get('text_receipt_is_offline') : $this->language->get('text_receipt_is_online') ,

				'fiscal_date'      		=> $this->checkBoxUA->parse_date($result['fiscal_date']), 
				'customer'      		=> $result['customer'], 
				'email'      			=> $result['email'], 
				'telephone'      		=> $result['telephone'], 
				'order_status'        	=> $result['order_status'],
				'total'        			=> $this->beautiful_price($result['total_national']) . ' ' . $result['currency_code'],
				'date_added'    		=> date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'date_modified' 		=> date($this->language->get('date_format_short'), strtotime($result['date_modified'])),
				'edit'          		=> $this->url->link('sale/receipt/edit', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'] . $url, true),
				'create'          		=> $this->url->link('sale/receipt/createOne', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'] . $url, true),
				'preview'          		=> $this->url->link('sale/receipt/preview', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'] . $url, true),
            );
		}

		$data['receipts_history'] = array();
		$receipts_history = $this->model_sale_receipt->getOrderReceipts();
		foreach ($receipts_history as $item) {
			$data['receipts_history'][] = array(
				'type' 				=> $item['type'],
				'receipt_id' 		=> $item['receipt_id'],
				'fiscal_date' 		=> $this->checkBoxUA->parse_date($item['fiscal_date']),
				'html_link'      	=> $this->checkBoxUA->getReceiptLink($item['receipt_id'],'html'),
				'pdf_link'      	=> $this->checkBoxUA->getReceiptLink($item['receipt_id'],'pdf'),
				'text_link'      	=> $this->checkBoxUA->getReceiptLink($item['receipt_id'],'text'),
				'qrcode_link'      	=> $this->checkBoxUA->getReceiptLink($item['receipt_id'],'qrcode'),
			); 
		}

		$data['shifts_history'] = array();
		$shifts_history = $this->model_sale_receipt->getShifts();
		foreach ($shifts_history as $item) {	
			$data['shifts_history'][] = array(
				'serial' 			=> $item['serial'],
				'z_report_id' 		=> $item['z_report_id'], 
				'text_link'      	=> $this->checkBoxUA->getReport($item['z_report_id']), 
			); 
		}

		$this->load->model('localisation/order_status');
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();		 		
 
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_fiscal_code'] = $this->language->get('column_fiscal_code');
		$data['column_order_id'] = $this->language->get('column_order_id');
		$data['column_customer'] = $this->language->get('column_customer');
		$data['column_product'] = $this->language->get('column_product');
		$data['column_model'] = $this->language->get('column_model');
		$data['column_status'] = $this->language->get('column_status');

		$data['column_date_added'] = $this->language->get('column_date_added');
		$data['column_date_modified'] = $this->language->get('column_date_modified');
		$data['column_action'] = $this->language->get('column_action');

		$data['entry_fiscal_code'] = $this->language->get('entry_fiscal_code');
		$data['entry_order_id'] = $this->language->get('entry_order_id');
		$data['entry_customer'] = $this->language->get('entry_customer');
		$data['entry_product'] = $this->language->get('entry_product');
		$data['entry_model'] = $this->language->get('entry_model');
		$data['entry_return_status'] = $this->language->get('entry_return_status');
		$data['entry_date_added'] = $this->language->get('entry_date_added');
		$data['entry_date_modified'] = $this->language->get('entry_date_modified');

		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_filter'] = $this->language->get('button_filter');

		$data['token'] = $this->session->data['token'];

		if (isset($this->session->data['error'])) {
			$data['error_warning'] = $this->session->data['error'];
			unset($this->session->data['error']);
		} elseif (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';
		if (isset($this->request->get['filter_fiscal_code'])) {
			$url .= '&filter_fiscal_code=' . $this->request->get['filter_fiscal_code'];
		}
		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}
		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_order_status'])) {
			$url .= '&filter_order_status=' . $this->request->get['filter_order_status'];
		}
		if (isset($this->request->get['filter_order_payment_code'])) {
			$url .= '&filter_order_payment_code=' . $this->request->get['filter_order_payment_code'];
		}
		if (isset($this->request->get['filter_has_receipt'])) {
			$url .= '&filter_has_receipt=' . $this->request->get['filter_has_receipt'];
		} 
		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_return_id'] = $this->url->link('sale/receipt', 'token=' . $this->session->data['token'] . '&sort=r.return_id' . $url, true);
		$data['sort_order_id'] = $this->url->link('sale/receipt', 'token=' . $this->session->data['token'] . '&sort=r.order_id' . $url, true);
		$data['sort_customer'] = $this->url->link('sale/receipt', 'token=' . $this->session->data['token'] . '&sort=customer' . $url, true);
		$data['sort_product'] = $this->url->link('sale/receipt', 'token=' . $this->session->data['token'] . '&sort=r.product' . $url, true);
		$data['sort_model'] = $this->url->link('sale/receipt', 'token=' . $this->session->data['token'] . '&sort=r.model' . $url, true);
		$data['sort_status'] = $this->url->link('sale/receipt', 'token=' . $this->session->data['token'] . '&sort=status' . $url, true);
		$data['sort_date_added'] = $this->url->link('sale/receipt', 'token=' . $this->session->data['token'] . '&sort=r.date_added' . $url, true);
		$data['sort_date_modified'] = $this->url->link('sale/receipt', 'token=' . $this->session->data['token'] . '&sort=r.date_modified' . $url, true);

		$url = '';

		if (isset($this->request->get['filter_fiscal_code'])) {
			$url .= '&filter_fiscal_code=' . $this->request->get['filter_fiscal_code'];
		}

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

			if (isset($this->request->get['filter_order_status'])) {
			$url .= '&filter_order_status=' . $this->request->get['filter_order_status'];
		}
		if (isset($this->request->get['filter_order_payment_code'])) {
			$url .= '&filter_order_payment_code=' . $this->request->get['filter_order_payment_code'];
		}

		if (isset($this->request->get['filter_has_receipt'])) {
			$url .= '&filter_has_receipt=' . $this->request->get['filter_has_receipt'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $receipt_total;
		$pagination->page = $page;
		$pagination->limit = $receipt_limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('sale/receipt', 'token=' . $this->session->data['token'] . $url . '&page={page}');

		$data['pagination'] = $pagination->render();


		$data['filter_fiscal_code'] 		= $filter_fiscal_code;
		$data['filter_order_id'] 			= $filter_order_id;
		$data['filter_customer'] 			= $filter_customer;
		$data['filter_order_status'] 		= $filter_order_status;
		$data['filter_order_payment_code'] 	= $filter_order_payment_code;
		$data['filter_has_receipt'] 		= $filter_has_receipt;
		$data['filter_date_added'] 			= $filter_date_added;
		$data['sort'] 						= $sort;
		$data['order'] 						= $order;

		###
		$data['current_shifts'] = $this->cache->get('current_shift');
		$data['extention_setting_link'] =  $this->url->link('module/receipt', 'token=' . $this->session->data['token'] , true);

		$data['all_payments'] = $this->model_sale_receipt->getAllSystemPayments();

		$data['column_left'] = '';

		$this->data = $data;
		$this->template = 'sale/receipt_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());

		#$this->response->setOutput($this->load->view('sale/receipt_list', $data));
	}

	protected function getPreviewForm() {

		$data['heading_title'] = $this->language->get('heading_title_preview');
		$data['text_form'] = '';
		$data['button_cancel'] = $this->language->get('button_cancel');

		$data['token'] = $this->session->data['token'];

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$url = '';
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('sale/receipt', 'token=' . $this->session->data['token'] . $url, true)
		); 
		$data['action'] = $this->url->link('sale/receipt/preview', 'token=' . $this->session->data['token'] . '&order_id=' . $this->request->get['order_id'] . $url, true);
		$data['cancel'] = $this->url->link('sale/receipt', 'token=' . $this->session->data['token'] . $url, true);


		$data['order_id'] = isset($this->request->get['order_id']) ? $this->request->get['order_id'] : 0;
		$data['create'] = $this->url->link('sale/receipt/createOne', 'token=' . $this->session->data['token'] . '&order_id=' . $data['order_id'], true);
		$data['extention_setting_link'] =  $this->url->link('module/receipt', 'token=' . $this->session->data['token'] , true);


	 
		#$this->response->setOutput($this->load->view('sale/receipt_form', $data));
		$data['column_left'] = '';
		$this->data = $data;
		$this->template = 'sale/receipt_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render()); 
	}

	private function beautiful_price($price_str){
		return number_format($price_str,2,'.','');
	}

	public function receipt_html_preview(){

		$order_id = isset($this->request->get['order_id']) ? $this->request->get['order_id'] : 0; 		 
		$data = $this->getPreviewData($order_id);
		$this->data = $data;
		$this->template = 'sale/receipt_html_preview.tpl'; 
		$this->response->setOutput($this->render()); 
	}

	protected function getPreviewData($order_id){
		$data['products'] = array();
		$order_info = $this->model_sale_receipt->getOrder($order_id);
		if(!$order_info){ return false;}
		
		$receipt_data = $this->checkBoxUA->checkReceipt($order_info);		

		if($receipt_data['goods']){
			foreach ($receipt_data['goods'] as $item) {
				$symbol_text = '';
				if(isset($item['good']['tax']) && $item['good']['tax'] ){					
					foreach ($item['good']['tax'] as $code) {
						$symbol_text .= $this->checkBoxUA->getTaxSymbolByCode($code);
					} 					
				}
				$data['products'][] = array(
					'name' 		=> $item['good']['name'],
					'symbol' 	=> $symbol_text,
					'quantity' 	=> ($item['quantity']/1000),
					'price' 	=> $this->beautiful_price ($item['good']['price']/100),
					'total' 	=> $this->beautiful_price ( ($item['good']['price']/100) * ($item['quantity']/1000)),
				);
			}
			
		}
		$data['discount_value'] = 0;
		if($receipt_data['discounts']){
			foreach ($receipt_data['discounts'] as $item) {
				$data['discount_name'] 	= $item['name'];
				$data['discount_value'] = $this->beautiful_price ( ($item['value']/100)*(-1) );
			}			
		}

		if($receipt_data['payments']){
			foreach ($receipt_data['payments'] as $item) {
				$data['payment_name'] = 'Картка';
				if($item['type'] == 'CASH'){
					$data['payment_name'] = '<span style="    background: yellow;">Готівка</span>';
				} 
				$data['payment_value'] = $this->beautiful_price($item['value']/100);
				$data['full_payment_data'] = array();
                if(isset($item['card_mask']) ){ 
                	if(!empty($item['label'])){
                		$data['payment_name'] = $item['label'];
                	}
                     
                     $data['full_payment_data'] = $item;
  
                }
			}			
		}

		if(!empty($receipt_data['footer'])){
			$data['footer'] = $receipt_data['footer'];			
		}

		if( $this->checkBoxUA->getTax() ){
			$data['taxes'] = $this->checkBoxUA->getTax();
		}

		return $data;
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'sale/receipt')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (empty($this->request->post['order_id'])) {
			$this->error['order_id'] = $this->language->get('error_order_id');
		}

		if ((utf8_strlen(trim($this->request->post['firstname'])) < 1) || (utf8_strlen(trim($this->request->post['firstname'])) > 32)) {
			$this->error['firstname'] = $this->language->get('error_firstname');
		}

		if ((utf8_strlen(trim($this->request->post['lastname'])) < 1) || (utf8_strlen(trim($this->request->post['lastname'])) > 32)) {
			$this->error['lastname'] = $this->language->get('error_lastname');
		}

		if ((utf8_strlen($this->request->post['email']) > 96) || !preg_match($this->config->get('config_mail_regexp'), $this->request->post['email'])) {
			$this->error['email'] = $this->language->get('error_email');
		}

		if ((utf8_strlen($this->request->post['telephone']) < 3) || (utf8_strlen($this->request->post['telephone']) > 32)) {
			$this->error['telephone'] = $this->language->get('error_telephone');
		}

		if ((utf8_strlen($this->request->post['product']) < 1) || (utf8_strlen($this->request->post['product']) > 255)) {
			$this->error['product'] = $this->language->get('error_product');
		}

		if ((utf8_strlen($this->request->post['model']) < 1) || (utf8_strlen($this->request->post['model']) > 64)) {
			$this->error['model'] = $this->language->get('error_model');
		}

		if (empty($this->request->post['return_reason_id'])) {
			$this->error['reason'] = $this->language->get('error_reason');
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'sale/receipt')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		return !$this->error;
	}

	protected function validateCreate() {
		if (!$this->user->hasPermission('modify', 'sale/receipt')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		return !$this->error;
	}

	public function changeOneSettingKey() {		
	}


	private function init() {
		$this->setting = $this->getSetting();		
		if (!empty($this->setting['login']) && !empty($this->setting['password'])) {		 		
			$this->checkBoxUA->setAuth($this->setting['login'], $this->setting['password'], $this->setting['x_license_key'], $this->setting['receipt_is_dev_mode']);
		}		 		
	}

	private function getSetting() {
		$receipt_setting['login'] 				= $this->config->get('receipt_login');
		$receipt_setting['password'] 			= $this->config->get('receipt_password');
		$receipt_setting['x_license_key'] 		= $this->config->get('receipt_x_license_key');
		$receipt_setting['receipt_is_dev_mode'] = $this->config->get('receipt_is_dev_mode');
		return $receipt_setting;
	}

	public function createOne(){
        $json = array();
		if(isset($this->request->get['order_id'])){
            $order_id = $this->request->get['order_id'];

            $order_info = $this->model_sale_receipt->getOrder($order_id);
            if($order_info){
                $json = $this->checkBoxUA->receiptsSell($order_info);
            }
		}

		if (!empty($json['success'])){
			$this->response->setOutput(json_encode($json['success']));
		} else {			
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
		}
	}

	public function createSelected($other_orders=array() ){
	    $receipt_data = array();
		$orders = array();

		if (isset($this->request->post['selected']) ) {
		    $orders  = $this->request->post['selected'];
		}elseif(isset($this->request->get['order_ids'])){
			$orders = $this->request->get['order_ids'];
		}elseif($other_orders){
            $orders = $other_orders;
        }

		if($orders){
            foreach ($orders as $order_id) {
                $order_info = $this->model_sale_receipt->getOrder($order_id);
                if($order_info){
                    $receipt_data[$order_id] = $this->checkBoxUA->receiptsSell($order_info);
                }
            }
		}

            if (isset($this->request->post['selected']) && $this->validateCreate()) {
                $success = '';
                foreach ($receipt_data as $order_id=>$receipt){
                    #de($receipt,1);
                    if(isset($receipt['error']['message'])){
                        $success .= "<BR>".$receipt['error']['message'];
                    }
                    if(isset($receipt['success'])){
                        $success .= "<BR> <b>NEW</b> ".$receipt['success'];
                    }

                }
                $this->session->data['success'] = $success;
                $url = '';
                if (isset($this->request->get['filter_fiscal_code'])) {
                    $url .= '&filter_fiscal_code=' . $this->request->get['filter_fiscal_code'];
                }
                if (isset($this->request->get['filter_order_id'])) {
                    $url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
                }
                if (isset($this->request->get['filter_customer'])) {
                    $url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
                }
                if (isset($this->request->get['filter_product'])) {
                    $url .= '&filter_product=' . urlencode(html_entity_decode($this->request->get['filter_product'], ENT_QUOTES, 'UTF-8'));
                }
                if (isset($this->request->get['filter_date_added'])) {
                    $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
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
                $this->redirect($this->url->link('sale/receipt', 'token=' . $this->session->data['token'] . $url, true));
            }
            $this->getList();
	}


    /*
     * Зміни (shift)
     */

    public function shiftOpen(){
        $this->init();
        $json = array();
        $json = $this->checkBoxUA->createShifts();
 
        $output = '';
        if(isset($json['id']) ){

            $output = '<div class="item">
		            <div class="col-sm-3">
		              <div class="form-group">Відбувається відкриття зміни
		              </div>            
		            </div>
		          </div>';

        }elseif(isset($json['message'])){
            $output = '<div class="item">
		            <div class="col-sm-3">
		              <div class="form-group">'.$json['message'].'
		              </div>            
		            </div>
		          </div>';
        }

        $this->shift();
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(($output));
    }

    public function shiftClose(){
        $this->init();
        $json = array();

        $json = $this->checkBoxUA->closeShifts();

        if(isset($json['id']) ){

            $output = '<div class="item">
		            <div class="col-sm-3">
		              <div class="form-group">Відбувається закриття зміни
		              </div>            
		            </div>
		          </div>';
			if(isset($json['z_report']['id']) && $json['z_report']['id']){
				$output .= '<script> modal_z_report("'.$this->checkBoxUA->getReport($json['z_report']['id']).'"); </script>';
			}

        }elseif(isset($json['message'])){
            $output = '<div class="item">
		            <div class="col-sm-3">
		              <div class="form-group">'.$json['message'].'
		              </div>            
		            </div>
		          </div>';
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput($output);
    }

	public function shift(){
		$output = '';
		$json = array();

		$current_shifts = $this->cache->get('current_shift');
		  
		if(!$current_shifts){
			$current_shifts = $this->checkBoxUA->getShifts();
		}
		if(isset($current_shifts['error'])){

			if(isset($current_shifts['error']['message'])){
				$error_text = $current_shifts['error']['message'];
			}else{
				$error_text = $current_shifts['error'];
			}

			$output = '<div class="item">
		            <div class="col-sm-3">
		              <div class="form-group">
		               
		                '.$error_text.'
		              </div>            
		            </div>
		           
		           
		          </div>';

		}elseif($current_shifts){

				$organization_title = $this->checkBoxUA->getCurrentOrganization(); 
				$json['opened_at'] = $this->checkBoxUA->parse_date($current_shifts['opened_at'],'H:i');
				$json['updated_at'] = !empty($current_shifts['balance']['updated_at']) ? $this->checkBoxUA->parse_date($current_shifts['balance']['updated_at'],'Y-m-d H:i'): '';
				$json['id'] = $current_shifts['id'];
				$json['status'] = $current_shifts['status'];
				$json['balance'] = $current_shifts['balance'];
				$json['balance']['updated_at'] = !empty($current_shifts['balance']['updated_at']) ? $this->checkBoxUA->parse_date($current_shifts['balance']['updated_at']): ''; 
		 
				$json['cash_register'] = $current_shifts['cash_register'];
				$json['cash_register']['created_at'] = $this->checkBoxUA->parse_date($current_shifts['cash_register']['created_at']);
				$json['cash_register']['updated_at'] = $this->checkBoxUA->parse_date($current_shifts['cash_register']['updated_at']);

				$datetime_opened_at = new DateTime($this->checkBoxUA->parse_date($current_shifts['opened_at'],'Y-m-d H:i'));
				$datetimeObj1 = new DateTime(date("Y-m-d H:i"));
				$datetimeObj2 = new DateTime( date("Y-m-d 23:59:59"));

				$interval_from_opened = $datetime_opened_at->diff($datetimeObj1); 
				$json['time_opened'] = $interval_from_opened->format('%H:%I'); 
 
 
				
				$interval_to_close = $datetimeObj1->diff($datetimeObj2); 
				$json['time_to_close'] = $interval_to_close->format('%H:%I');


  				 
  					$json['opened_at'] = $json['opened_at']. '  [тривалість: ' .$json['time_opened'] . '] ';
  				 
  				if($interval_to_close->format('%H') < 3){
  					$json['opened_at'] .= '<br><span style="color:red;font-size: 17px;"> необхідно закрити через '  . ' ['.$json['time_to_close'].' год.] </span>';
  				}
		
		  
				$output = '<span style=" position: absolute;  right: 5px;  bottom: 5px;">Організація:<b> '.$organization_title.' </b></span> <div class="item">
		            <div class="col-sm-3">
		            
		             
		              <div class="form-group">
		                <label class="control-label" for="input-return-id">Статус :</label>
		                <span style="color: #75a74d;   font-size: 15px;     font-weight: bold;">'.($json['status'] == "OPENED" ? "Відкрито" : "0" ).'</span>
		             <br>
		                <label class="control-label" for="input-return-id">час відкриття:</label>
		                '.$json['opened_at'].' ';
 

			         $output .= ' 
		              </div>            
		            </div>

		            <div class="col-sm-3 "> 
		                       
		              <div class="form-group">
		              <h4>💵 Готівкові кошти в касі </h4>
		              <span style=" font-size: 22px;">
		                 '.($json['balance']['balance']/100).' грн.</span>
		                 <br>
		              </div> 
				
					  
		            </div>
		           
		            <div class="col-sm-3 "> 
		                       
		              <div class="form-group">
		              <h4>💵 Виручка готівкою</h4>  
		              <span style=" font-size: 22px;">
		                 '.($json['balance']['cash_sales']/100).' грн.</span>
		              </div> 
				
					  
		            </div>


		           <div class="col-sm-3">
		          
		              	<div class="form-group">
		              	 <h4>💳 Виручка безготівкова</h4> 
		              	  <span style=" font-size: 22px;">
		                 '.($json['balance']['card_sales']/100).' грн.</span>

				              </div> 
		              </div>
		            
		           
		          </div>';

		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput($output);
	}

    public function shiftUpdate(){ 
    	$this->cache->set('current_shift', '');
        $this->cache->delete('current_shift');
        $this->shift();
    }

	/*
	 * Створення сервісного чеку внесення або винесення коштів, та його фіскалізація.
	 */
	public function receipts_service(){
		$this->init(); 
		$json = array();

		$service_data = array(
			'service_operation' => (isset($this->request->get['service_operation']) ? $this->request->get['service_operation'] : '' ),
			'service_value' => (isset($this->request->get['service_value']) ? $this->request->get['service_value'] : 0 ),
			'service_type' => (isset($this->request->get['service_type']) ? $this->request->get['service_type'] : '' ),
		);
 	
 		if($service_data['service_value']>0){
 			$json = $this->checkBoxUA->receiptsService($service_data);
			$this->shiftUpdate();
 		}else{
 			$json['error']['message'] = 'Введіть сумму';
 		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}



	public function createCron($other_orders=array() ){
	    $receipt_data = array();
		$orders = array();

		if($other_orders){
            $orders = $other_orders;
        }

		if($orders){
            foreach ($orders as $order_id) {
                $order_info = $this->model_sale_receipt->getOrder($order_id);
                if($order_info){
                    $receipt_data[$order_id] = $this->checkBoxUA->receiptsSell($order_info);
                }
            }
		}
		        
        $message = '';
        foreach ($receipt_data as $order_id => $receipt){
            if(isset($receipt['error']['message'])){
                $message .= $receipt['error']['message'];
            }

            if(isset($receipt['success'])){
                $message .= $receipt['success'];
            }
        }
        
        echoLine($message, 'i');
	}
    
    public function cron() {    	
        //DO NOTHING IN BETWEEN 23:00 AND 01:00
        if (!is_cli()){
        	echoLine('[ControllerSaleReceipt::cron] ONLY CLI MODE', 'e');
			exit();
        }

        if (!$this->config->get('receipt_login')){
        	echoLine('[ControllerSaleReceipt::cron] CheckBox DISABLED', 'e');
			exit();
        }

        $interval = new \hobotix\Interval('23:00' . '-' . '01:00');
        if ($interval->isNow()){
        	echoLine('[ControllerSaleReceipt::cron] NOT ALLOWED TIME ' . date('H:i'), 'e');
        	return;
        } else {
        	echoLine('[ControllerSaleReceipt::cron] ALLOWED TIME ' . date('H:i'), 's');				
        }

        $filter_data = array(
        //  'filter_order_status'        		=> $this->config->get('receipt_order_status_id'),
		//	'filter_order_payment_code'        	=> $this->config->get('receipt_cron_order_payment_code'),
        	'filter_needs_checkboxua'			=> 1,
            'filter_has_receipt'         		=> 0,
            'filter_date_modified_2day'       	=> date('Y-m-d'),
            'start'                   			=> 0,
            'limit'                   			=> $this->limit
        );

        $receipt_total 	= $this->model_sale_receipt->getTotalOrders($filter_data);
        $results 		= $this->model_sale_receipt->getOrders($filter_data);

        echoLine('[ControllerSaleReceipt::cron] Selected ' . $receipt_total . ' orders', 'i');

        if($results){
            $this->init();           
            $current_shifts = $this->checkBoxUA->getShifts();

            if((isset($current_shifts['error']) || !isset($current_shifts['id'])) ){             	
            	if($this->config->get('receipt_cron_auto_shifts_open') && date('H') <= $this->config->get('receipt_cron_auto_shifts_open_hour')){
            		echoLine('[ControllerSaleReceipt::cron] Started auto shift opening process', 'i');
            		               
                	$new_shift = $this->checkBoxUA->createShifts();
                	sleep(2);
                	if(isset($new_shift['id'])){
	                	echoLine('[ControllerSaleReceipt::cron] Shift is opened with ID: ' . $new_shift['id'], 's');                   
	                    $current_shifts = $this->checkBoxUA->getShifts();
	                    sleep(1);
	                } else {
	                	echoLine('[ControllerSaleReceipt::cron] Shift does not open! Exiting due to error!', 'e'); 
	                	die();
	                }

            	} else { 
            		echoLine('[ControllerSaleReceipt::cron] Shift does not open! Exiting due to error!', 'e'); 
            		return; 
				}    	       
            }            

            if (empty($current_shifts['id'])){
            	echoLine('[ControllerSaleReceipt::cron] Shift does not open! Exiting due to error!', 'e'); 
	             die();
            }

            echoLine('[ControllerSaleReceipt::cron] Working in shift ' . $current_shifts['id'], 'e');     
			
            $order_ids = array();
            foreach ($results as $order){
                $order_ids[] = $order['order_id'];
            }

            if($order_ids){
            	echoLine('[ControllerSaleReceipt::cron] Creating receipts for orders: ' . implode(',', $order_ids));            
            	$this->createCron($order_ids);
            }
        }

        $processingReceipts = $this->model_sale_receipt->getProcessingReceipts();
    	if ($processingReceipts){
    		echoLine('[ControllerSaleReceipt::cron] Have processing receipts, ' . count($processingReceipts), 'i');

    		foreach($processingReceipts as $processingReceiptID){
    			$this->checkBoxUA->getReceipt($processingReceiptID);    			
    		}
    	}

    	$receipt_total 	= $this->model_sale_receipt->getTotalOrders($filter_data);
    	$results 		= $this->model_sale_receipt->getOrders($filter_data);

    	if ($receipt_total > 0){
    		if ($this->config->get('receipt_tg_send_alerts')){
    			$telegramBot = new \Longman\TelegramBot\Telegram($this->config->get('receipt_tg_bot_token'), 'BotName');

    			$message = '⚠️ CheckBox ПРРО' . PHP_EOL . PHP_EOL;
    			$message .= '⚠️ Наразі маємо <b>' . $receipt_total . '</b> нефіскалізованих на даний момент замовлень!' . PHP_EOL . PHP_EOL;

    			foreach ($results as $order){
                	$message .= '💀 Замовлення ' . $order['order_id'] . ', на суму ' . $order['total_national']  . PHP_EOL;
            	}

            	$message .= PHP_EOL;
    			$message .= 'ℹ️ Якщо це повідомлення буде повторено декілька разів - це є проблемою, оскільки щось пішло не так з передачею даних до ДФС';

    			try {
    				$result = \Longman\TelegramBot\Request::sendMessage([
    					'chat_id' 		=> $this->config->get('receipt_tg_bot_group_id'),
    					'text'    		=> $message,
    					'parse_mode' 	=> 'HTML',
    				]);

    			} catch (\Longman\TelegramBot\Exception\TelegramException $e) {
    				echoLine($e->getMessage(), 'e');
    			}
    		} else {
    			echoLine('[ControllerSaleReceipt::cron] Some more orders left!', 'e');
    		}			
    	}
    }
}
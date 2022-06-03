<?php

class ControllerReportExportXLS extends Controller{

	private $objPHPExcel;			// Use to create our Excel worksheet
	private $mainCounter;			// The actual order when using 'Export all'
	private $filter_date_start; 	// The start date from data filter
	private $filter_date_end; 		// The end date from data filter
	private $filter_order_status_id;// Status order filter
	
		private function bool_real_stripos($haystack, $needle){
		
		return !(stripos($haystack, $needle) === false);
		
	}
	
	public function index(){

		// Loading language file
		$this->load->language('report/export_xls');
		$this->document->setTitle($this->language->get('heading_title'));


		// Check if date start, date end and status were set as filter
		if (isset($this->request->get['filter_date_start'])) {
			$this->filter_date_start = $this->request->get['filter_date_start'];
		} else 
			$this->filter_date_start = date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01'));
		if (isset($this->request->get['filter_date_end'])) {
			$this->filter_date_end = $this->request->get['filter_date_end'];
		} else 
			$this->filter_date_end = date('Y-m-d');
		if (isset($this->request->get['filter_order_status_id'])) {
			$this->filter_order_status_id = $this->request->get['filter_order_status_id'];
		} else
			$this->filter_order_status_id = 0;


		// Add filter dates to the url (GET)
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


		// Creating breadcrumb
		$this->data['breadcrumbs'] 		= array();
   		$this->data['breadcrumbs'][] 	= array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('report/export_xls', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);


   		// Set the text to the view from language file
		$this->data['heading_title'] 	= $this->language->get('heading_title');
		$this->data['text_customer'] 	= $this->language->get('text_customer');
		$this->data['text_date'] 		= $this->language->get('text_date');
		$this->data['text_order'] 		= $this->language->get('text_order');
		$this->data['text_amount'] 		= $this->language->get('text_amount');
		$this->data['text_product'] 	= $this->language->get('text_product');
		$this->data['text_status']		= $this->language->get('text_status');
		$this->data['text_action'] 		= $this->language->get('text_action');
		$this->data['text_export_all']	= $this->language->get('text_export_all');
		$this->data['text_all_status']	= $this->language->get('text_all_status');
		$this->data['entry_date_start'] = $this->language->get('entry_date_start');
		$this->data['entry_date_end'] 	= $this->language->get('entry_date_end');
		$this->data['entry_status']		= $this->language->get('entry_status');
		$this->data['button_export'] 	= $this->url->link('report/export_xls/export', 'token=' . $this->session->data['token'] . $url . '&order=all', 'SSL');
		$this->data['button_filter'] 	= $this->language->get('button_filter');


		// Loading order status model
		$this->load->model('localisation/order_status');
    	$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();


		// Loading export xls model
		$this->load->model('report/export_xls');
		$data = array(
			'filter_date_start'	     => $this->filter_date_start, 
			'filter_date_end'	     => $this->filter_date_end,
			'filter_order_status_id' => $this->filter_order_status_id
		);
		$results = $this->model_report_export_xls->getOrders($data);
		$this->data['orders'] = array();



		// Loop on all results (orders) found
		foreach ($results as $result) {

			// Create action associate to each order (line)
			$action = array();
			$action[] = array(
				'text' 	=> $this->language->get('text_view'),
				'href' 	=> $this->url->link('sale/order/info', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'], 'SSL')
			);
			$action[] = array(
				'text' 	=> $this->language->get('text_export'),
				'href'	=> $this->url->link('report/export_xls/export', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'], 'SSL')
			);
			$action[] = array(
				'text' 	=> $this->language->get('text_invoice'),
				'href'	=> $this->url->link('report/export_xls/createInvoice', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'], 'SSL')
			);


			// Create new line
			$this->data['orders'][] = array(
				'firstname' 	=> $result['firstname'],
				'lastname'  	=> $result['lastname'],
				'nb_product'	=> $this->model_report_export_xls->getTotalProductFromOrder($result['order_id']),
				'order_id'  	=> $result['order_id'],
				'date_added'	=> date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'total'     	=> $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
				'status'		=> $result['name'],
				'action' 		=> $action
			);
		}


		// Send to the view the filter's value + token
		$this->data['token'] = $this->session->data['token'];
		$this->data['filter_date_start'] = $this->filter_date_start;
		$this->data['filter_date_end'] = $this->filter_date_end;
		$this->data['filter_order_status_id'] = $this->filter_order_status_id;


		// Use export xls template to display datas
		$this->template = 'report/export_xls.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);


		// Display
		$this->response->setOutput($this->render());
	}

	private function getDownloadXlsFile($order_id = null){
		// Redirect output to a client’s web browser (Excel2007)
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		$filename = prepareFileName('Спецификация заказа №'.$order_id.'.xlsx');
		header('Content-Disposition: attachment;filename="'.$filename.'"');
		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($this->objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		exit();
	}
	
	
	private function getDownloadXlsFileOfMoscowDelivery($_date, $_country = 'Россия'){
		
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		$filename = prepareFileName('З_1M ЗАЯВКА ДОСТАВКА '.date('d.m.Y', strtotime($_date)).' ' . $_country .'.xlsx');
		header('Content-Disposition: attachment;filename="'.$filename.'"');
		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($this->objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		exit();
		
	}
	
	private function getDownloadXlsFileOfNowWhere($_date){
		
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		$filename = prepareFileName('Текущие заказы '.date('d.m.Y', strtotime($_date)).'.xlsx');
		header('Content-Disposition: attachment;filename="'.$filename.'"');
		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($this->objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		exit();
		
	}

	public function export(){
		
		// Loading PHP Excel library
	
		// Create a new worksheet Excel and set the counter at 1
		$this->objPHPExcel = new PHPExcel();
		$this->mainCounter = 1;


		// If it's an individual export
		if(isset($this->request->get['order_id'])){
			$order_id = $this->request->get['order_id'];// Get the order ID
			$this->createExcelWorksheet($order_id);		// Create the Excel worksheet
			$this->getDownloadXlsFile($order_id);		// Then download the file
		}


		// If user click on "Export all"
		if(isset($this->request->get['order'])) {
			$this->load->model('report/export_xls');	
			$data = array(
				'filter_date_start'	     => $this->filter_date_start, 
				'filter_date_end'	     => $this->filter_date_end,
				'filter_order_status_id' => $this->filter_order_status_id
			);
			$result = $this->model_report_export_xls->getOrders($data);

			foreach ($result as $res) {
				$this->createExcelWorksheet($res['order_id']);
				$this->mainCounter++;
			}
			$this->getDownloadXlsFile('all');
		}
	}
	
	public function createProductsInvoiceWithImage(){
					// Loading Excel Library
		
		


		// Loading language file
		$this->load->language('report/export_xls');


		// Loading model
		$this->load->model('report/export_xls');
		$this->load->model('sale/order');
		

		// If an order is define, we get the number
		if(isset($this->request->get['order_id'])){
			$order_id = $this->request->get['order_id'];
		} else { 
			$order_id = 0;
		}


		// Get the information relative to the order
		$result = $this->model_report_export_xls->getOrder($order_id);


		// Adding order info to an array
		foreach ($result as $res) {
			$this->data['orders'][] = array(
				'order_id' 				=> $res['order_id'],
				'store_name' 			=> $res['store_name'],
				'customer' 				=> $res['firstname'] . ' ' . $res['lastname'],
				'email'					=> $res['email'],
				'telephone'				=> $res['telephone'],
				'total'					=> $this->currency->format($res['total'], $res['currency_code'], $res['currency_value']),
				'date_added'			=> date($this->language->get('date_format_short'), strtotime($res['date_added'])),

				'currency_code'			=> $res['currency_code'],
				'currency_value'		=> $res['currency_value'],

				'payment_firstname'		=> $res['payment_firstname'],
				'payment_lastname'		=> $res['payment_lastname'],
				'payment_address_1'		=> $res['payment_address_1'],
				'payment_address_2'		=> $res['payment_address_2'],
				'payment_city'			=> $res['payment_city'],
				'payment_postcode'		=> $res['payment_postcode'],
				'payment_zone'			=> $res['payment_zone'],
				'payment_country'		=> $res['payment_country'],
				'payment_method'		=> $res['payment_method'],

				'shipping_firstname'	=> $res['shipping_firstname'],
				'shipping_lastname'		=> $res['shipping_lastname'],
				'shipping_address_1'	=> $res['shipping_address_1'],
				'shipping_address_2'	=> $res['shipping_address_2'],
				'shipping_city'			=> $res['shipping_city'],
				'shipping_postcode'		=> $res['shipping_postcode'],
				'shipping_zone'			=> $res['shipping_zone'],
				'shipping_country'		=> $res['shipping_country'],
				'shipping_method'		=> $res['shipping_method']
			);
		}

		// Put the index of the array into invoice (more simple to call)
		$invoice = $this->data['orders'][0];


		// Creating a new instance of Excel with properties
		$this->objPHPExcel = new PHPExcel();
		$this->objPHPExcel->getProperties()->setCreator("SUPERENGINE")
										   ->setLastModifiedBy("SUPERENGINE")
										   ->setTitle("Office 2007 XLSX")
										   ->setSubject("Office 2007 XLSX")
										   ->setDescription("Document for Office 2007 XLSX, generated by SUPERENGINE")
										   ->setKeywords("office 2007 excel")
										   ->setCategory("SUPERENGINE");


		// Create a first sheet, representing order data
		$this->objPHPExcel->setActiveSheetIndex(0);


		// Writing store info left top
		


		// Writing general info about the order
		$this->objPHPExcel->getActiveSheet()->setCellValue('B1', 'Спецификация заказа #'.$invoice['order_id']);
		$this->objPHPExcel->getActiveSheet()->setCellValue('C1', $invoice['date_added']);
		$this->objPHPExcel->getActiveSheet()->getStyle('B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$this->objPHPExcel->getActiveSheet()->getStyle('C1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);


		// Set thin black border outline around column
		$styleThinBlackBorderOutline = array(
			'borders' => array(
				'outline' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN,
					'color' => array('argb' => 'FF000000'),
				),
			),
		);
		


		// Product header
		$this->objPHPExcel->getActiveSheet()->getStyle('A3')->applyFromArray($styleThinBlackBorderOutline);
		$this->objPHPExcel->getActiveSheet()->getStyle('A3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$this->objPHPExcel->getActiveSheet()->getStyle('A3')->getFill()->getStartColor()->setARGB('E7EFEF');
		$this->objPHPExcel->getActiveSheet()->setCellValue('A3', 'Картинка');
		
		$this->objPHPExcel->getActiveSheet()->getStyle('B3')->applyFromArray($styleThinBlackBorderOutline);
		$this->objPHPExcel->getActiveSheet()->getStyle('B3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$this->objPHPExcel->getActiveSheet()->getStyle('B3')->getFill()->getStartColor()->setARGB('E7EFEF');
		$this->objPHPExcel->getActiveSheet()->setCellValue('B3', 'Наименование');
		
		$this->objPHPExcel->getActiveSheet()->getStyle('C3')->applyFromArray($styleThinBlackBorderOutline);
		$this->objPHPExcel->getActiveSheet()->getStyle('C3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$this->objPHPExcel->getActiveSheet()->getStyle('C3')->getFill()->getStartColor()->setARGB('E7EFEF');
		$this->objPHPExcel->getActiveSheet()->setCellValue('C3', 'Bezeichnung');
		
		$this->objPHPExcel->getActiveSheet()->getStyle('D3')->applyFromArray($styleThinBlackBorderOutline);
		$this->objPHPExcel->getActiveSheet()->getStyle('D3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$this->objPHPExcel->getActiveSheet()->getStyle('D3')->getFill()->getStartColor()->setARGB('E7EFEF');
		$this->objPHPExcel->getActiveSheet()->setCellValue('D3', 'Артикул');
		
		$this->objPHPExcel->getActiveSheet()->getStyle('E3')->applyFromArray($styleThinBlackBorderOutline);
		$this->objPHPExcel->getActiveSheet()->getStyle('E3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$this->objPHPExcel->getActiveSheet()->getStyle('E3')->getFill()->getStartColor()->setARGB('E7EFEF');
		$this->objPHPExcel->getActiveSheet()->setCellValue('E3', 'EAN');
		
		$this->objPHPExcel->getActiveSheet()->getStyle('F3')->applyFromArray($styleThinBlackBorderOutline);
		$this->objPHPExcel->getActiveSheet()->getStyle('F3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$this->objPHPExcel->getActiveSheet()->getStyle('F3')->getFill()->getStartColor()->setARGB('E7EFEF');
		$this->objPHPExcel->getActiveSheet()->setCellValue('F3', 'ТН ВЭД');
		
		$this->objPHPExcel->getActiveSheet()->getStyle('G3')->applyFromArray($styleThinBlackBorderOutline);
		$this->objPHPExcel->getActiveSheet()->getStyle('G3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$this->objPHPExcel->getActiveSheet()->getStyle('G3')->getFill()->getStartColor()->setARGB('E7EFEF');
		$this->objPHPExcel->getActiveSheet()->setCellValue('G3', 'Кол-во, ');
		
		$this->objPHPExcel->getActiveSheet()->getStyle('H3')->applyFromArray($styleThinBlackBorderOutline);
		$this->objPHPExcel->getActiveSheet()->getStyle('H3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$this->objPHPExcel->getActiveSheet()->getStyle('H3')->getFill()->getStartColor()->setARGB('E7EFEF');
		$this->objPHPExcel->getActiveSheet()->setCellValue('H3', 'Цена, '.$invoice['currency_code']);
		
		$this->objPHPExcel->getActiveSheet()->getStyle('I3')->applyFromArray($styleThinBlackBorderOutline);
		$this->objPHPExcel->getActiveSheet()->getStyle('I3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$this->objPHPExcel->getActiveSheet()->getStyle('I3')->getFill()->getStartColor()->setARGB('E7EFEF');
		$this->objPHPExcel->getActiveSheet()->setCellValue('I3', 'Итого, '.$invoice['currency_code']);


		// Writing product details
		$products = $this->model_report_export_xls->getProductListFromOrder($order_id);
		$counter = 4;

		$this->load->model('catalog/product');
		$this->load->model('tool/image');

		
		
		foreach ($products as $prod) {
		
			$objDrawing = new PHPExcel_Worksheet_Drawing();
			$objDrawing->setPath(DIR_IMAGE . $prod['image']);
			$objDrawing->setHeight(64);
			$objDrawing->setCoordinates('A'. $counter);
			$objDrawing->setWorksheet($this->objPHPExcel->getActiveSheet());
			$objDrawing->setOffsetX(5);
			$objDrawing->setOffsetY(5);

			// Add each product line
			$this->objPHPExcel->getActiveSheet()->setCellValue('B' . $counter, html_entity_decode($prod['name'], ENT_QUOTES, 'UTF-8'));
			$this->objPHPExcel->getActiveSheet()->setCellValue('C' . $counter, html_entity_decode($this->model_catalog_product->getProductDeName($prod['product_id']), ENT_QUOTES, 'UTF-8'));
			$this->objPHPExcel->getActiveSheet()->setCellValueExplicit('D' . $counter, html_entity_decode($prod['model'], ENT_QUOTES, 'UTF-8'), PHPExcel_Cell_DataType::TYPE_STRING);
			
			$this->objPHPExcel->getActiveSheet()->setCellValueExplicit('E' . $counter, html_entity_decode($prod['ean'], ENT_QUOTES, 'UTF-8'), PHPExcel_Cell_DataType::TYPE_STRING);
			
			$this->objPHPExcel->getActiveSheet()->setCellValueExplicit('F' . $counter, html_entity_decode($prod['tnved'], ENT_QUOTES, 'UTF-8'), PHPExcel_Cell_DataType::TYPE_STRING);
			
			$this->objPHPExcel->getActiveSheet()->setCellValue('G' . $counter, $prod['quantity']);
			/*
			$this->objPHPExcel->getActiveSheet()->setCellValue('E' . $counter, $this->currency->format($prod['price_national'], $invoice['currency_code'], '1'));
			$this->objPHPExcel->getActiveSheet()->setCellValue('F' . $counter, $this->currency->format($prod['total_national'], $invoice['currency_code'], '1'));
			*/
			$this->objPHPExcel->getActiveSheet()->setCellValue('H' . $counter, $prod['price_national']);
			$this->objPHPExcel->getActiveSheet()->setCellValue('I' . $counter, $prod['total_national']);
			
			$this->objPHPExcel->getActiveSheet()->getRowDimension($counter)->setRowHeight(54);
			$this->objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(54);
			
			$counter++;
		}


		// Draw a frame around the products list
		$this->objPHPExcel->getActiveSheet()->getStyle('F4:F'.($counter-1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->objPHPExcel->getActiveSheet()->getStyle('G4:G'.($counter-1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		
		
		//totals
		$counter++;
		$totals = $this->model_sale_order->getOrderTotals($order_id);
		
		foreach ($totals as $total){
			$this->objPHPExcel->getActiveSheet()->setCellValue('A' . $counter, $total['title']);
			$this->objPHPExcel->getActiveSheet()->getStyle('A'.$counter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			
			$this->objPHPExcel->getActiveSheet()->mergeCells('A'.$counter.':G'.$counter);
			
			$this->objPHPExcel->getActiveSheet()->setCellValueExplicit('H' . $counter, html_entity_decode(number_format($total['value_national'],0,'',''), ENT_QUOTES, 'UTF-8'), PHPExcel_Cell_DataType::TYPE_STRING);
			
			$this->objPHPExcel->getActiveSheet()->setCellValueExplicit('I' . $counter, html_entity_decode($res['currency_code'], ENT_QUOTES, 'UTF-8'), PHPExcel_Cell_DataType::TYPE_STRING);
			
			$counter++;
		}
		

		// Set the orientation page an give a name to the worksheet
		$this->objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
		$this->objPHPExcel->getActiveSheet()->setTitle('Спецификация заказа #' . $order_id);


		// Set the column in autosize
		$columns = array('A','B','C','D','E','F','G','H', 'I');
		foreach($columns as $col){
			$this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
			$this->objPHPExcel->getActiveSheet()->getStyle($col.'4:'.$col.$counter)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		}
		$this->objPHPExcel->getActiveSheet()->getStyle('A:I')->getFont()->setSize(14);

		$this->getDownloadXlsFile($order_id);
		
		
	}
	
	public function createProductsInvoice(){
					// Loading Excel Library
		
		


		// Loading language file
		$this->load->language('report/export_xls');


		// Loading model
		$this->load->model('report/export_xls');
		$this->load->model('sale/order');
		

		// If an order is define, we get the number
		if(isset($this->request->get['order_id'])){
			$order_id = $this->request->get['order_id'];
		} else { 
			$order_id = 0;
		}


		// Get the information relative to the order
		$result = $this->model_report_export_xls->getOrder($order_id);


		// Adding order info to an array
		foreach ($result as $res) {
			$this->data['orders'][] = array(
				'order_id' 				=> $res['order_id'],
				'store_name' 			=> $res['store_name'],
				'customer' 				=> $res['firstname'] . ' ' . $res['lastname'],
				'email'					=> $res['email'],
				'telephone'				=> $res['telephone'],
				'total'					=> $this->currency->format($res['total'], $res['currency_code'], $res['currency_value']),
				'date_added'			=> date($this->language->get('date_format_short'), strtotime($res['date_added'])),

				'currency_code'			=> $res['currency_code'],
				'currency_value'		=> $res['currency_value'],

				'payment_firstname'		=> $res['payment_firstname'],
				'payment_lastname'		=> $res['payment_lastname'],
				'payment_address_1'		=> $res['payment_address_1'],
				'payment_address_2'		=> $res['payment_address_2'],
				'payment_city'			=> $res['payment_city'],
				'payment_postcode'		=> $res['payment_postcode'],
				'payment_zone'			=> $res['payment_zone'],
				'payment_country'		=> $res['payment_country'],
				'payment_method'		=> $res['payment_method'],

				'shipping_firstname'	=> $res['shipping_firstname'],
				'shipping_lastname'		=> $res['shipping_lastname'],
				'shipping_address_1'	=> $res['shipping_address_1'],
				'shipping_address_2'	=> $res['shipping_address_2'],
				'shipping_city'			=> $res['shipping_city'],
				'shipping_postcode'		=> $res['shipping_postcode'],
				'shipping_zone'			=> $res['shipping_zone'],
				'shipping_country'		=> $res['shipping_country'],
				'shipping_method'		=> $res['shipping_method']
			);
		}

		// Put the index of the array into invoice (more simple to call)
		$invoice = $this->data['orders'][0];


		// Creating a new instance of Excel with properties
		$this->objPHPExcel = new PHPExcel();
		$this->objPHPExcel->getProperties()->setCreator("SUPERENGINE")
										   ->setLastModifiedBy("SUPERENGINE")
										   ->setTitle("Office 2007 XLSX")
										   ->setSubject("Office 2007 XLSX")
										   ->setDescription("Document for Office 2007 XLSX, generated by SUPERENGINE")
										   ->setKeywords("office 2007 excel")
										   ->setCategory("SUPERENGINE");


		// Create a first sheet, representing order data
		$this->objPHPExcel->setActiveSheetIndex(0);


		// Writing store info left top
		


		// Writing general info about the order
		$this->objPHPExcel->getActiveSheet()->setCellValue('A1', 'Спецификация заказа #'.$invoice['order_id']);
		$this->objPHPExcel->getActiveSheet()->setCellValue('B1', $invoice['date_added']);
		$this->objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$this->objPHPExcel->getActiveSheet()->getStyle('B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);


		// Set thin black border outline around column
		$styleThinBlackBorderOutline = array(
			'borders' => array(
				'outline' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN,
					'color' => array('argb' => 'FF000000'),
				),
			),
		);
		


		// Product header
		$this->objPHPExcel->getActiveSheet()->getStyle('A3')->applyFromArray($styleThinBlackBorderOutline);
		$this->objPHPExcel->getActiveSheet()->getStyle('A3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$this->objPHPExcel->getActiveSheet()->getStyle('A3')->getFill()->getStartColor()->setARGB('E7EFEF');
		$this->objPHPExcel->getActiveSheet()->setCellValue('A3', 'Наименование');
		
		$this->objPHPExcel->getActiveSheet()->getStyle('B3')->applyFromArray($styleThinBlackBorderOutline);
		$this->objPHPExcel->getActiveSheet()->getStyle('B3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$this->objPHPExcel->getActiveSheet()->getStyle('B3')->getFill()->getStartColor()->setARGB('E7EFEF');
		$this->objPHPExcel->getActiveSheet()->setCellValue('B3', 'Bezeichnung');
		
		$this->objPHPExcel->getActiveSheet()->getStyle('C3')->applyFromArray($styleThinBlackBorderOutline);
		$this->objPHPExcel->getActiveSheet()->getStyle('C3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$this->objPHPExcel->getActiveSheet()->getStyle('C3')->getFill()->getStartColor()->setARGB('E7EFEF');
		$this->objPHPExcel->getActiveSheet()->setCellValue('C3', 'Артикул');
		
		$this->objPHPExcel->getActiveSheet()->getStyle('D3')->applyFromArray($styleThinBlackBorderOutline);
		$this->objPHPExcel->getActiveSheet()->getStyle('D3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$this->objPHPExcel->getActiveSheet()->getStyle('D3')->getFill()->getStartColor()->setARGB('E7EFEF');
		$this->objPHPExcel->getActiveSheet()->setCellValue('D3', 'EAN');
		
		$this->objPHPExcel->getActiveSheet()->getStyle('E3')->applyFromArray($styleThinBlackBorderOutline);
		$this->objPHPExcel->getActiveSheet()->getStyle('E3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$this->objPHPExcel->getActiveSheet()->getStyle('E3')->getFill()->getStartColor()->setARGB('E7EFEF');
		$this->objPHPExcel->getActiveSheet()->setCellValue('E3', 'ТН ВЭД');
		
		$this->objPHPExcel->getActiveSheet()->getStyle('F3')->applyFromArray($styleThinBlackBorderOutline);
		$this->objPHPExcel->getActiveSheet()->getStyle('F3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$this->objPHPExcel->getActiveSheet()->getStyle('F3')->getFill()->getStartColor()->setARGB('E7EFEF');
		$this->objPHPExcel->getActiveSheet()->setCellValue('F3', 'Кол.-во');
		
		$this->objPHPExcel->getActiveSheet()->getStyle('G3')->applyFromArray($styleThinBlackBorderOutline);
		$this->objPHPExcel->getActiveSheet()->getStyle('G3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$this->objPHPExcel->getActiveSheet()->getStyle('G3')->getFill()->getStartColor()->setARGB('E7EFEF');
		$this->objPHPExcel->getActiveSheet()->setCellValue('G3', 'Цена, '.$invoice['currency_code']);
		
		$this->objPHPExcel->getActiveSheet()->getStyle('H3')->applyFromArray($styleThinBlackBorderOutline);
		$this->objPHPExcel->getActiveSheet()->getStyle('H3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$this->objPHPExcel->getActiveSheet()->getStyle('H3')->getFill()->getStartColor()->setARGB('E7EFEF');
		$this->objPHPExcel->getActiveSheet()->setCellValue('H3', 'Итого, '.$invoice['currency_code']);


		// Writing product details
		$products = $this->model_report_export_xls->getProductListFromOrder($order_id);
		$counter = 4;

		$this->load->model('catalog/product');
		$this->load->model('tool/image');
		
		foreach ($products as $prod) {		

			// Add each product line
			$this->objPHPExcel->getActiveSheet()->setCellValue('A' . $counter, html_entity_decode($prod['name'], ENT_QUOTES, 'UTF-8'));
			$this->objPHPExcel->getActiveSheet()->setCellValue('B' . $counter, html_entity_decode($this->model_catalog_product->getProductDeName($prod['product_id']), ENT_QUOTES, 'UTF-8'));
			$this->objPHPExcel->getActiveSheet()->setCellValueExplicit('C' . $counter, html_entity_decode($prod['model'], ENT_QUOTES, 'UTF-8'), PHPExcel_Cell_DataType::TYPE_STRING);
			
			$this->objPHPExcel->getActiveSheet()->setCellValueExplicit('D' . $counter, html_entity_decode($prod['ean'], ENT_QUOTES, 'UTF-8'), PHPExcel_Cell_DataType::TYPE_STRING);
			
			$this->objPHPExcel->getActiveSheet()->setCellValueExplicit('E' . $counter, html_entity_decode($prod['tnved'], ENT_QUOTES, 'UTF-8'), PHPExcel_Cell_DataType::TYPE_STRING);
			
			$this->objPHPExcel->getActiveSheet()->setCellValue('F' . $counter, $prod['quantity']);
			/*
			$this->objPHPExcel->getActiveSheet()->setCellValue('E' . $counter, $this->currency->format($prod['price_national'], $invoice['currency_code'], '1'));
			$this->objPHPExcel->getActiveSheet()->setCellValue('F' . $counter, $this->currency->format($prod['total_national'], $invoice['currency_code'], '1'));
			*/
			$this->objPHPExcel->getActiveSheet()->setCellValue('G' . $counter, $prod['price_national']);
			$this->objPHPExcel->getActiveSheet()->setCellValue('H' . $counter, $prod['total_national']);
			$counter++;
		}


		// Draw a frame around the products list
		$this->objPHPExcel->getActiveSheet()->getStyle('E4:E'.($counter-1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->objPHPExcel->getActiveSheet()->getStyle('F4:F'.($counter-1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		
		//totals
		$counter++;
		$totals = $this->model_sale_order->getOrderTotals($order_id);
		
		foreach ($totals as $total){
			$this->objPHPExcel->getActiveSheet()->setCellValue('A' . $counter, $total['title']);
			$this->objPHPExcel->getActiveSheet()->getStyle('A'.$counter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			
			$this->objPHPExcel->getActiveSheet()->mergeCells('A'.$counter.':F'.$counter);
			
			$this->objPHPExcel->getActiveSheet()->setCellValueExplicit('G' . $counter, html_entity_decode(number_format($total['value_national'],0,'',''), ENT_QUOTES, 'UTF-8'), PHPExcel_Cell_DataType::TYPE_STRING);
			
			$this->objPHPExcel->getActiveSheet()->setCellValueExplicit('H' . $counter, html_entity_decode($res['currency_code'], ENT_QUOTES, 'UTF-8'), PHPExcel_Cell_DataType::TYPE_STRING);
			
			$counter++;
		}
		

		// Set the orientation page an give a name to the worksheet
		$this->objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
		$this->objPHPExcel->getActiveSheet()->setTitle('Спецификация заказа #' . $order_id);


		// Set the column in autosize
		$columns = array('A','B','C','D','E','F','G','H');
		foreach($columns as $col){
			$this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		}
		$this->objPHPExcel->getActiveSheet()->getStyle('A:H')->getFont()->setSize(14);

		$this->getDownloadXlsFile($order_id);
		
	}

	public function createInvoice(){

		// Loading Excel Library
		
		// Loading language file
		$this->load->language('report/export_xls');


		// Loading model
		$this->load->model('report/export_xls');
		$this->load->model('sale/order');
		

		// If an order is define, we get the number
		if(isset($this->request->get['order_id'])){
			$order_id = $this->request->get['order_id'];
		}else $order_id = 0;


		// Get the information relative to the order
		$result = $this->model_report_export_xls->getOrder($order_id);


		// Adding order info to an array
		foreach ($result as $res) {
			$this->data['orders'][] = array(
				'order_id' 				=> $res['order_id'],
				'store_name' 			=> $res['store_name'],
				'customer' 				=> $res['firstname'] . ' ' . $res['lastname'],
				'email'					=> $res['email'],
				'telephone'				=> $res['telephone'],
				'total'					=> $this->currency->format($res['total'], $res['currency_code'], $res['currency_value']),
				'date_added'			=> date($this->language->get('date_format_short'), strtotime($res['date_added'])),

				'currency_code'			=> $res['currency_code'],
				'currency_value'		=> $res['currency_value'],

				'payment_firstname'		=> $res['payment_firstname'],
				'payment_lastname'		=> $res['payment_lastname'],
				'payment_address_1'		=> $res['payment_address_1'],
				'payment_address_2'		=> $res['payment_address_2'],
				'payment_city'			=> $res['payment_city'],
				'payment_postcode'		=> $res['payment_postcode'],
				'payment_zone'			=> $res['payment_zone'],
				'payment_country'		=> $res['payment_country'],
				'payment_method'		=> $res['payment_method'],

				'shipping_firstname'	=> $res['shipping_firstname'],
				'shipping_lastname'		=> $res['shipping_lastname'],
				'shipping_address_1'	=> $res['shipping_address_1'],
				'shipping_address_2'	=> $res['shipping_address_2'],
				'shipping_city'			=> $res['shipping_city'],
				'shipping_postcode'		=> $res['shipping_postcode'],
				'shipping_zone'			=> $res['shipping_zone'],
				'shipping_country'		=> $res['shipping_country'],
				'shipping_method'		=> $res['shipping_method']
			);
		}

		// Put the index of the array into invoice (more simple to call)
		$invoice = $this->data['orders'][0];


		// Creating a new instance of Excel with properties
		$this->objPHPExcel = new PHPExcel();
		$this->objPHPExcel->getProperties()->setCreator("SUPERENGINE")
										   ->setLastModifiedBy("SUPERENGINE")
										   ->setTitle("Office 2007 XLSX")
										   ->setSubject("Office 2007 XLSX")
										   ->setDescription("Document for Office 2007 XLSX, generated by SUPERENGINE")
										   ->setKeywords("office 2007 excel")
										   ->setCategory("SUPERENGINE");


		// Create a first sheet, representing order data
		$this->objPHPExcel->setActiveSheetIndex(0);


		// Writing store info left top
		$this->objPHPExcel->getActiveSheet()->setCellValue('A4', $this->config->get('config_name'));
		$this->objPHPExcel->getActiveSheet()->setCellValue('A5', $this->config->get('config_address'));
		$this->objPHPExcel->getActiveSheet()->setCellValue('A6', 'Телефон : ' . $this->config->get('config_telephone'));
		$this->objPHPExcel->getActiveSheet()->setCellValue('A7', $this->config->get('config_email'));


		// Writing general info about the order
		$this->objPHPExcel->getActiveSheet()->setCellValue('G4', $this->language->get('header_date'));
		$this->objPHPExcel->getActiveSheet()->setCellValue('G5', $this->language->get('header_order_id'));
		$this->objPHPExcel->getActiveSheet()->setCellValue('G6', $this->language->get('header_payment_method'));
		$this->objPHPExcel->getActiveSheet()->setCellValue('G7', $this->language->get('header_shipping_method'));
		$this->objPHPExcel->getActiveSheet()->getStyle('G4')->getFont()->setBold(true);
		$this->objPHPExcel->getActiveSheet()->getStyle('G5')->getFont()->setBold(true);
		$this->objPHPExcel->getActiveSheet()->getStyle('G6')->getFont()->setBold(true);
		$this->objPHPExcel->getActiveSheet()->getStyle('G7')->getFont()->setBold(true);
		$this->objPHPExcel->getActiveSheet()->setCellValue('H4', $invoice['date_added']);
		$this->objPHPExcel->getActiveSheet()->setCellValue('H5', $invoice['order_id']);
		$this->objPHPExcel->getActiveSheet()->setCellValue('H6', $invoice['payment_method']);
		$this->objPHPExcel->getActiveSheet()->setCellValue('H7', $invoice['shipping_method']);
		$this->objPHPExcel->getActiveSheet()->getStyle('H4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$this->objPHPExcel->getActiveSheet()->getStyle('H5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$this->objPHPExcel->getActiveSheet()->getStyle('H6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$this->objPHPExcel->getActiveSheet()->getStyle('H7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);


		// Set thin black border outline around column
		$styleThinBlackBorderOutline = array(
			'borders' => array(
				'outline' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN,
					'color' => array('argb' => 'FF000000'),
				),
			),
		);


		// Address info header
		$this->objPHPExcel->getActiveSheet()->getStyle('A11:D11')->applyFromArray($styleThinBlackBorderOutline);
		$this->objPHPExcel->getActiveSheet()->getStyle('A11:D11')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$this->objPHPExcel->getActiveSheet()->getStyle('A11:D11')->getFill()->getStartColor()->setARGB('E7EFEF');
		$this->objPHPExcel->getActiveSheet()->setCellValue('A11', $this->language->get('header_to'));
		$this->objPHPExcel->getActiveSheet()->mergeCells('A11:D11');
		$this->objPHPExcel->getActiveSheet()->getStyle('E11:H11')->applyFromArray($styleThinBlackBorderOutline);
		$this->objPHPExcel->getActiveSheet()->getStyle('E11:H11')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$this->objPHPExcel->getActiveSheet()->getStyle('E11:H11')->getFill()->getStartColor()->setARGB('E7EFEF');
		$this->objPHPExcel->getActiveSheet()->setCellValue('E11', $this->language->get('header_ship_to'));
		$this->objPHPExcel->getActiveSheet()->mergeCells('E11:H11');
		$this->objPHPExcel->getActiveSheet()->getStyle('A12:D19')->applyFromArray($styleThinBlackBorderOutline);
		$this->objPHPExcel->getActiveSheet()->getStyle('E12:H19')->applyFromArray($styleThinBlackBorderOutline);


		// Write customer info
		$this->objPHPExcel->getActiveSheet()->setCellValue('A12', $invoice['payment_firstname'] . ' ' . $invoice['payment_lastname']);
		$this->objPHPExcel->getActiveSheet()->setCellValue('A13', $invoice['payment_address_1']);
		$this->objPHPExcel->getActiveSheet()->setCellValue('A14', $invoice['payment_address_2']);
		$this->objPHPExcel->getActiveSheet()->setCellValue('A15', $invoice['payment_city'] . ' ' . $invoice['payment_postcode']);
		$this->objPHPExcel->getActiveSheet()->setCellValue('A16', $invoice['payment_zone']);
		$this->objPHPExcel->getActiveSheet()->setCellValue('A17', $invoice['payment_country']);
		$this->objPHPExcel->getActiveSheet()->setCellValue('A18', $invoice['email']);
		$this->objPHPExcel->getActiveSheet()->setCellValue('A19', $invoice['telephone']);


		// Write shipping info
		$this->objPHPExcel->getActiveSheet()->setCellValue('E12', $invoice['shipping_firstname'] . ' ' . $invoice['shipping_lastname']);
		$this->objPHPExcel->getActiveSheet()->setCellValue('E13', $invoice['shipping_address_1']);
		$this->objPHPExcel->getActiveSheet()->setCellValue('E14', $invoice['shipping_address_2']);
		$this->objPHPExcel->getActiveSheet()->setCellValue('E15', $invoice['shipping_city'] . ' ' . $invoice['shipping_postcode']);
		$this->objPHPExcel->getActiveSheet()->setCellValue('E16', $invoice['shipping_zone']);
		$this->objPHPExcel->getActiveSheet()->setCellValue('E17', $invoice['shipping_country']);


		// Product header
		$this->objPHPExcel->getActiveSheet()->getStyle('A21:C21')->applyFromArray($styleThinBlackBorderOutline);
		$this->objPHPExcel->getActiveSheet()->getStyle('A21:C21')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$this->objPHPExcel->getActiveSheet()->getStyle('A21:C21')->getFill()->getStartColor()->setARGB('E7EFEF');
		$this->objPHPExcel->getActiveSheet()->setCellValue('A21', $this->language->get('header_product_name'));
		$this->objPHPExcel->getActiveSheet()->mergeCells('A21:C21');
		$this->objPHPExcel->getActiveSheet()->getStyle('D21:E21')->applyFromArray($styleThinBlackBorderOutline);
		$this->objPHPExcel->getActiveSheet()->getStyle('D21:E21')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$this->objPHPExcel->getActiveSheet()->getStyle('D21:E21')->getFill()->getStartColor()->setARGB('E7EFEF');
		$this->objPHPExcel->getActiveSheet()->setCellValue('D21', $this->language->get('header_product_model'));
		$this->objPHPExcel->getActiveSheet()->mergeCells('D21:E21');
		$this->objPHPExcel->getActiveSheet()->getStyle('F21')->applyFromArray($styleThinBlackBorderOutline);
		$this->objPHPExcel->getActiveSheet()->getStyle('F21')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$this->objPHPExcel->getActiveSheet()->getStyle('F21')->getFill()->getStartColor()->setARGB('E7EFEF');
		$this->objPHPExcel->getActiveSheet()->setCellValue('F21', $this->language->get('header_product_quantity'));
		$this->objPHPExcel->getActiveSheet()->getStyle('G21')->applyFromArray($styleThinBlackBorderOutline);
		$this->objPHPExcel->getActiveSheet()->getStyle('G21')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$this->objPHPExcel->getActiveSheet()->getStyle('G21')->getFill()->getStartColor()->setARGB('E7EFEF');
		$this->objPHPExcel->getActiveSheet()->setCellValue('G21', $this->language->get('header_product_unit_price'));
		$this->objPHPExcel->getActiveSheet()->getStyle('H21')->applyFromArray($styleThinBlackBorderOutline);
		$this->objPHPExcel->getActiveSheet()->getStyle('H21')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$this->objPHPExcel->getActiveSheet()->getStyle('H21')->getFill()->getStartColor()->setARGB('E7EFEF');
		$this->objPHPExcel->getActiveSheet()->setCellValue('H21', $this->language->get('header_product_price'));


		// Writing product details
		$products = $this->model_report_export_xls->getProductListFromOrder($order_id);
		$counter = 22;

		foreach ($products as $prod) {
			$option_data = array();

			// Get the product option to get the color and the size
			$options = $this->model_sale_order->getOrderOptions($order_id, $prod['order_product_id']);

			if(!empty($options)){
				foreach ($options as $option) {
					if ($option['name'] == 'Size') {
						$option_data['Size'][] = array(
							'name'  => $option['name'],
							'value' => $option['value'],
							'type'  => $option['type']
						);
					} 
					if ($option['name'] == 'Color') {
						$option_data['Color'][] = array(
							'name'  => $option['name'],
							'value' => $option['value'],
							'type'  => $option['type']
						);
					} 
				}
			}

			$color = ''; $size = '';
			if( !empty($option_data['Color']) ){
				$color = '[Color : ' . $option_data['Color'][0]['value'] . ']';
			}
			if( !empty($option_data['Size']) ){
				$size = '[Size : ' . $option_data['Size'][0]['value'] . ']';
			}

			// Add each product line
			$this->objPHPExcel->getActiveSheet()->setCellValue('A' . $counter, html_entity_decode($prod['name'], ENT_QUOTES, 'UTF-8') . ' ' . $color . ' ' . $size);
			$this->objPHPExcel->getActiveSheet()->setCellValueExplicit('D' . $counter, html_entity_decode('Арт. ' . $prod['model'], ENT_QUOTES, 'UTF-8'), PHPExcel_Cell_DataType::TYPE_STRING);
			$this->objPHPExcel->getActiveSheet()->setCellValue('F' . $counter, $prod['quantity']);
			$this->objPHPExcel->getActiveSheet()->setCellValue('G' . $counter, $this->currency->format($prod['price'], $invoice['currency_code'], $invoice['currency_value']));
			$this->objPHPExcel->getActiveSheet()->setCellValue('H' . $counter, $this->currency->format($prod['total'], $invoice['currency_code'], $invoice['currency_value']));

			$counter++;
		}


		// Draw a frame around the products list
		$this->objPHPExcel->getActiveSheet()->getStyle('A22:C'.($counter-1))->applyFromArray($styleThinBlackBorderOutline);
		$this->objPHPExcel->getActiveSheet()->getStyle('D22:E'.($counter-1))->applyFromArray($styleThinBlackBorderOutline);
		$this->objPHPExcel->getActiveSheet()->getStyle('F22:F'.($counter-1))->applyFromArray($styleThinBlackBorderOutline);
		$this->objPHPExcel->getActiveSheet()->getStyle('G22:G'.($counter-1))->applyFromArray($styleThinBlackBorderOutline);
		$this->objPHPExcel->getActiveSheet()->getStyle('H22:H'.($counter-1))->applyFromArray($styleThinBlackBorderOutline);


		// Get the sub total, tax and total from this order
		$total_data = $this->model_sale_order->getOrderTotals($order_id);
		

		// Write this under the product list on the right
		foreach ($total_data as $total) {
			$this->objPHPExcel->getActiveSheet()->setCellValue('G'.$counter, $total['title']);
			$this->objPHPExcel->getActiveSheet()->setCellValue('H'.$counter, $total['text']);
			$this->objPHPExcel->getActiveSheet()->getStyle('G'.$counter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$this->objPHPExcel->getActiveSheet()->getStyle('G'.$counter)->getFont()->setBold(true);
			$this->objPHPExcel->getActiveSheet()->getStyle('H'.$counter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$this->objPHPExcel->getActiveSheet()->getStyle('G'.$counter.':H'.$counter)->applyFromArray($styleThinBlackBorderOutline);

			$counter++;
		}


		// Add the shop logo on the top of the document
		$this->objPHPExcel->getActiveSheet()->insertNewRowBefore(1,1);
		$objDrawing = new PHPExcel_Worksheet_Drawing();
		$objDrawing->setPath(DIR_IMAGE . $this->config->get('config_logo'));
		$objDrawing->setHeight(64);
		$objDrawing->setCoordinates('A1');
		$objDrawing->setWorksheet($this->objPHPExcel->getActiveSheet());


		// Set the orientation page an give a name to the worksheet
		$this->objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
		$this->objPHPExcel->getActiveSheet()->setTitle('Invoice #' . $order_id);


		// Set the column in autosize
		$columns = array('A',/*'B','C','D','E','F',*/'G','H');
		foreach($columns as $col){
			$this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		}
		$this->objPHPExcel->getActiveSheet()->getStyle('A:H')->getFont()->setSize(10);


		$this->objPHPExcel->getActiveSheet()->removeColumn('B', 2);


		$this->getDownloadXlsFile($order_id);
	}
	
	public function createOrderNowWhere(){
		$_date = date('Y-m-d');
		if (!$_date || mb_strlen($_date)<10){
			return false;
		}
		
		// Loading Excel Library
		
		
				
		$this->load->model('report/export_xls');
		$this->load->model('sale/order');
		$this->load->model('catalog/product');
		$this->load->model('localisation/order_status');
		
		$this->objPHPExcel = new PHPExcel();
		$this->objPHPExcel->getProperties()->setCreator("SUPERENGINE")
										   ->setLastModifiedBy("SUPERENGINE")
										   ->setTitle("Office 2007 XLSX")
										   ->setSubject("Office 2007 XLSX")
										   ->setDescription("Document for Office 2007 XLSX, generated by SUPERENGINE")
										   ->setKeywords("office 2007 excel")
										   ->setCategory("SUPERENGINE");
		
		$_order_string = "44642,44642,44644,44644,44644,44644,44649,44649,44649,44649,44649,44649,44649,44649,44649,44649,44656,44656,44656,44673,44688,44688,44688,44688,44688,44708,44708,44708,44708,44708,44708,44708,44708,44708,44708,44708,44708,44708,44708,44708,44708,44708,44708,44708,44708,44708,44708,44708,44708,44708,44708,44708,44708,44708,44708,44708,44708,44712,44718,44718,44738,44744,44744,44744,44744,44746,44747,44749,44750,44750,44750,44750,44750,44750,44750,44778,44788,44789,44792,44792,44792,44792,44793,44793,44793,44793,44810,44828,44828,44828,44828,44828,44828,44828,44829,44829,44837,44840,44841,44841,44841,44841,44841,44842,44842,44848,44850,44850,44850,44873,44873,44873,44873,44873,44884,44884,44884,44889,44889,44889,44896,44909,44914,44914,44934,44934,44711,44711,44974,44974,44923,44923,44923,44923,44928,44929,44929,44929,44929,44930,44930,44930,44645,44689,44689,44689,44779,44796,44839,44851,44856,44856,44874,44874,44874,44874,44888,44915,44667,44667,44822,44822,44852,44852,44852,44852,44852,44852,44852,44875,44882,44748,44748,44921,44921,44666,44730,44741,44795,44795,44811,44978,44978,44978,44997,44998,44998,44999,44999,44999,44999,45013,45013,45020,45020,45021,45021,45092,45092,45092,45092,45092,45092,45092,45264,45265,45297,45297,45305,45310,45312,45163,45163,45163,45163,45163,45163,45163,45163,45163,45163,45163,45163,45163,45163,45163,45163,45163,45163,45163,45163,45163,45163,45163,45163,45163,45163,45163,45163,45163,45163,45163,45163,45163,45036,45041,45049,45049,45049,45049,45063,45063,45063,45063,45063,45063,45063,45063,45063,45063,45063,45063,45063,45083,45083,45083,45083,45083,45083,45108,45113,45135,45139,45139,45139,45139,45139,45139,45139,45139,45139,45139,45149,45156,45156,45156,45160,45160,45169,45169,45178,45302,45302,45302,45302,45302,45302,45302,45302,45302,45302,45302,45302,45302,45302,45302,45307,45307,45314,45314,44717,44925,45037,45037,45037,45040,45142,45142,45177,45177,45177";
		
		//Россия
		$this->objPHPExcel->setActiveSheetIndex(0);		
		$orders = $this->db->query("SELECT * FROM `order` WHERE (store_id = 0 OR store_id = 2)  AND date_added >= '2017-01-01' AND order_status_id IN (2, 19, 15, 14, 16) AND NOT order_id IN (" . $_order_string . ")");
		$this->objPHPExcel->getActiveSheet()->setTitle("Российская Федерация, Казахстан");
		
		$_row = 2;
		foreach ($orders->rows as $_order){
			$products = $this->model_sale_order->getOrderProducts($_order['order_id']);	
			$order_status = $this->model_localisation_order_status->getOrderStatus($_order['order_status_id']);				
			
			$this->objPHPExcel->getActiveSheet()->setCellValue('A1', 'Заказ Покупателя');
			$this->objPHPExcel->getActiveSheet()->setCellValue('B1', 'Дата Заказа');
			$this->objPHPExcel->getActiveSheet()->setCellValue('C1', 'Артикул');
			$this->objPHPExcel->getActiveSheet()->setCellValue('D1', 'Наименование');
			$this->objPHPExcel->getActiveSheet()->setCellValue('E1', 'EAN');
			$this->objPHPExcel->getActiveSheet()->setCellValue('F1', 'Кол-во');
			$this->objPHPExcel->getActiveSheet()->setCellValue('G1', 'Страна');
			$this->objPHPExcel->getActiveSheet()->setCellValue('H1', 'Цена');
			$this->objPHPExcel->getActiveSheet()->setCellValue('I1', 'Сумма');
			$this->objPHPExcel->getActiveSheet()->setCellValue('J1', 'Дата получения');
			$this->objPHPExcel->getActiveSheet()->setCellValue('K1', 'Статус Заказа');
			
			foreach ($products as $_product){
				$real_product = $this->model_catalog_product->getProduct($_product['product_id']);
				
				$_de_name = $this->model_catalog_product->getProductDeName($_product['product_id']);				
				
				if ($_de_name){
					$_product['name'] = $_de_name;
				} elseif ($real_product['short_name']) {
					$_product['name'] = $real_product['short_name'];
				}
				
				$_product['name'] = str_replace('&amp;', '&', $_product['name']);
				
				
				$this->objPHPExcel->getActiveSheet()->setCellValue('A'.$_row, $_order['order_id']);
				$this->objPHPExcel->getActiveSheet()->setCellValue('B'.$_row, date('Y-m-d', strtotime($_order['date_added'])));
				$this->objPHPExcel->getActiveSheet()->setCellValueExplicit('C'.$_row, str_replace(array(' ', '.'),array('',''), $_product['model']), PHPExcel_Cell_DataType::TYPE_STRING);
				$this->objPHPExcel->getActiveSheet()->setCellValue('D'.$_row, $_product['name']);
				$this->objPHPExcel->getActiveSheet()->setCellValueExplicit('E'.$_row, $real_product['ean'], PHPExcel_Cell_DataType::TYPE_STRING);
				$this->objPHPExcel->getActiveSheet()->setCellValue('F'.$_row, $_product['quantity']);
				$this->objPHPExcel->getActiveSheet()->setCellValueExplicit('G'.$_row, 'Российская Федерация', PHPExcel_Cell_DataType::TYPE_STRING);
				$this->objPHPExcel->getActiveSheet()->setCellValueExplicit('H'.$_row, $_product['price'], PHPExcel_Cell_DataType::TYPE_STRING);
				$this->objPHPExcel->getActiveSheet()->setCellValueExplicit('I'.$_row, $_product['total'], PHPExcel_Cell_DataType::TYPE_STRING);
				$this->objPHPExcel->getActiveSheet()->setCellValue('J'.$_row, '');
				$this->objPHPExcel->getActiveSheet()->setCellValue('K'.$_row, $order_status['name']);
				
				$_row++;												
				
			}
		}
		
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
		
		
		//УКРАИНА
		$objWorkSheet = $this->objPHPExcel->createSheet(1);
		$this->objPHPExcel->setActiveSheetIndex(1);
		$orders = $this->db->query("SELECT * FROM `order` WHERE store_id = 1 AND date_added >= '2017-01-01' AND order_status_id IN (2, 19, 15, 14, 16) AND NOT order_id IN (" . $_order_string . ")");
		$this->objPHPExcel->getActiveSheet()->setTitle("Украина");
		
		$_row = 2;
		foreach ($orders->rows as $_order){
			$products = $this->model_sale_order->getOrderProducts($_order['order_id']);
			$order_status = $this->model_localisation_order_status->getOrderStatus($_order['order_status_id']);		
			
			$this->objPHPExcel->getActiveSheet()->setCellValue('A1', 'Заказ Покупателя');
			$this->objPHPExcel->getActiveSheet()->setCellValue('B1', 'Дата Заказа');
			$this->objPHPExcel->getActiveSheet()->setCellValue('C1', 'Артикул');
			$this->objPHPExcel->getActiveSheet()->setCellValue('D1', 'Наименование');
			$this->objPHPExcel->getActiveSheet()->setCellValue('E1', 'EAN');
			$this->objPHPExcel->getActiveSheet()->setCellValue('F1', 'Кол-во');
			$this->objPHPExcel->getActiveSheet()->setCellValue('G1', 'Страна');
			$this->objPHPExcel->getActiveSheet()->setCellValue('H1', 'Цена');
			$this->objPHPExcel->getActiveSheet()->setCellValue('I1', 'Сумма');
			$this->objPHPExcel->getActiveSheet()->setCellValue('J1', 'Дата получения');
			$this->objPHPExcel->getActiveSheet()->setCellValue('K1', 'Статус Заказа');
			
			foreach ($products as $_product){
				$real_product = $this->model_catalog_product->getProduct($_product['product_id']);
				
				$_de_name = $this->model_catalog_product->getProductDeName($_product['product_id']);
				
				if ($_de_name){
					$_product['name'] = $_de_name;
				} elseif ($real_product['short_name']) {
					$_product['name'] = $real_product['short_name'];
				}
				
				$_product['name'] = str_replace('&amp;', '&', $_product['name']);
				
				
				$this->objPHPExcel->getActiveSheet()->setCellValue('A'.$_row, $_order['order_id']);
				$this->objPHPExcel->getActiveSheet()->setCellValue('B'.$_row, date('Y-m-d', strtotime($_order['date_added'])));
				$this->objPHPExcel->getActiveSheet()->setCellValueExplicit('C'.$_row, str_replace(array(' ', '.'),array('',''), $_product['model']), PHPExcel_Cell_DataType::TYPE_STRING);
				$this->objPHPExcel->getActiveSheet()->setCellValue('D'.$_row, $_product['name']);
				$this->objPHPExcel->getActiveSheet()->setCellValueExplicit('E'.$_row, $real_product['ean'], PHPExcel_Cell_DataType::TYPE_STRING);
				$this->objPHPExcel->getActiveSheet()->setCellValue('F'.$_row, $_product['quantity']);
				$this->objPHPExcel->getActiveSheet()->setCellValueExplicit('G'.$_row, 'Украина', PHPExcel_Cell_DataType::TYPE_STRING);
				$this->objPHPExcel->getActiveSheet()->setCellValueExplicit('H'.$_row, $_product['price'], PHPExcel_Cell_DataType::TYPE_STRING);
				$this->objPHPExcel->getActiveSheet()->setCellValueExplicit('I'.$_row, $_product['total'], PHPExcel_Cell_DataType::TYPE_STRING);
				$this->objPHPExcel->getActiveSheet()->setCellValue('J'.$_row, '');
				$this->objPHPExcel->getActiveSheet()->setCellValue('K'.$_row, $order_status['name']);
				
				$_row++;												
				
			}
		}
		
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
		
		//БЕЛОРУССИЯ
		$objWorkSheet = $this->objPHPExcel->createSheet(2);
		$this->objPHPExcel->setActiveSheetIndex(2);
		$orders = $this->db->query("SELECT * FROM `order` WHERE store_id = 5 AND date_added >= '2017-01-01' AND order_status_id IN (2, 19, 15, 14, 16) AND NOT order_id IN (" . $_order_string . ")");
		$this->objPHPExcel->getActiveSheet()->setTitle("Белоруссия");
		
		$_row = 2;
		foreach ($orders->rows as $_order){
			$products = $this->model_sale_order->getOrderProducts($_order['order_id']);
			$order_status = $this->model_localisation_order_status->getOrderStatus($_order['order_status_id']);		
			
			$this->objPHPExcel->getActiveSheet()->setCellValue('A1', 'Заказ Покупателя');
			$this->objPHPExcel->getActiveSheet()->setCellValue('B1', 'Дата Заказа');
			$this->objPHPExcel->getActiveSheet()->setCellValue('C1', 'Артикул');
			$this->objPHPExcel->getActiveSheet()->setCellValue('D1', 'Наименование');
			$this->objPHPExcel->getActiveSheet()->setCellValue('E1', 'EAN');
			$this->objPHPExcel->getActiveSheet()->setCellValue('F1', 'Кол-во');
			$this->objPHPExcel->getActiveSheet()->setCellValue('G1', 'Страна');
			$this->objPHPExcel->getActiveSheet()->setCellValue('H1', 'Цена');
			$this->objPHPExcel->getActiveSheet()->setCellValue('I1', 'Сумма');
			$this->objPHPExcel->getActiveSheet()->setCellValue('J1', 'Дата получения');
			$this->objPHPExcel->getActiveSheet()->setCellValue('K1', 'Статус Заказа');
			
			foreach ($products as $_product){
				$real_product = $this->model_catalog_product->getProduct($_product['product_id']);
				
				$_de_name = $this->model_catalog_product->getProductDeName($_product['product_id']);
				
				if ($_de_name){
					$_product['name'] = $_de_name;
				} elseif ($real_product['short_name']) {
					$_product['name'] = $real_product['short_name'];
				}
				
				$_product['name'] = str_replace('&amp;', '&', $_product['name']);				
				
				$this->objPHPExcel->getActiveSheet()->setCellValue('A'.$_row, $_order['order_id']);
				$this->objPHPExcel->getActiveSheet()->setCellValue('B'.$_row, date('Y-m-d', strtotime($_order['date_added'])));
				$this->objPHPExcel->getActiveSheet()->setCellValueExplicit('C'.$_row, str_replace(array(' ', '.'),array('',''), $_product['model']), PHPExcel_Cell_DataType::TYPE_STRING);
				$this->objPHPExcel->getActiveSheet()->setCellValue('D'.$_row, $_product['name']);
				$this->objPHPExcel->getActiveSheet()->setCellValueExplicit('E'.$_row, $real_product['ean'], PHPExcel_Cell_DataType::TYPE_STRING);
				$this->objPHPExcel->getActiveSheet()->setCellValue('F'.$_row, $_product['quantity']);
				$this->objPHPExcel->getActiveSheet()->setCellValueExplicit('G'.$_row, 'Белоруссия', PHPExcel_Cell_DataType::TYPE_STRING);
				$this->objPHPExcel->getActiveSheet()->setCellValueExplicit('H'.$_row, $_product['price'], PHPExcel_Cell_DataType::TYPE_STRING);
				$this->objPHPExcel->getActiveSheet()->setCellValueExplicit('I'.$_row, $_product['total'], PHPExcel_Cell_DataType::TYPE_STRING);
				$this->objPHPExcel->getActiveSheet()->setCellValue('J'.$_row, '');
				$this->objPHPExcel->getActiveSheet()->setCellValue('K'.$_row, $order_status['name']);
				
				$_row++;												
				
			}
		}
		
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
		
		
		$this->getDownloadXlsFileOfNowWhere($_date);
	}
	
	
	public function createOrderDeliveryMoscow(){
		$_date = date('Y-m-d', strtotime($this->request->get['date']));
		if (!$_date || mb_strlen($_date)<10){
			return false;
		}
		
		// Loading Excel Library
		
		

		
		$this->load->model('report/export_xls');
		$this->load->model('sale/order');
		$this->load->model('catalog/product');
		
	
		$this->objPHPExcel = new PHPExcel();
		$this->objPHPExcel->getProperties()->setCreator("SUPERENGINE")
										   ->setLastModifiedBy("SUPERENGINE")
										   ->setTitle("Office 2007 XLSX")
										   ->setSubject("Office 2007 XLSX")
										   ->setDescription("Document for Office 2007 XLSX, generated by SUPERENGINE")
										   ->setKeywords("office 2007 excel")
										   ->setCategory("SUPERENGINE");


		// Create a first sheet, representing order data
		$this->objPHPExcel->setActiveSheetIndex(0);

		$this->objPHPExcel->getActiveSheet()->setCellValue('A1', '');
		$this->objPHPExcel->getActiveSheet()->setCellValue('B1', 'КатегорияТовара');
		$this->objPHPExcel->getActiveSheet()->setCellValue('C1', 'Артикул');
		$this->objPHPExcel->getActiveSheet()->setCellValue('D1', 'Количество');
		$this->objPHPExcel->getActiveSheet()->setCellValue('E1', 'ЦенаЗакупочная');
		$this->objPHPExcel->getActiveSheet()->setCellValue('F1', 'ЦенаПродажи');
		$this->objPHPExcel->getActiveSheet()->setCellValue('G1', 'Штрихкод');
		$this->objPHPExcel->getActiveSheet()->setCellValue('H1', 'КодЗаказаКонтрагента');
		$this->objPHPExcel->getActiveSheet()->setCellValue('I1', 'Покупатель');
		$this->objPHPExcel->getActiveSheet()->setCellValue('J1', 'ТелНомер');
		$this->objPHPExcel->getActiveSheet()->setCellValue('K1', 'АдресДоставки');
		$this->objPHPExcel->getActiveSheet()->setCellValue('L1', 'Город');
		$this->objPHPExcel->getActiveSheet()->setCellValue('M1', 'Примечания');
		$this->objPHPExcel->getActiveSheet()->setCellValue('N1', 'Вес');
		$this->objPHPExcel->getActiveSheet()->setCellValue('O1', 'Контрагент');
		
		
		//getOrders
		if (isset($this->request->get['shipping_country_id'])){
			$_country_id = (int)$this->request->get['shipping_country_id'];
		} else {
			$_country_id = 176;
		}
		
		$orders = $this->db->query("SELECT * FROM `order` WHERE shipping_country_id = " . (int)$_country_id . " AND date_delivery_actual = '" . $this->db->escape($_date) . "'");
		
		$_order_counter = 3;
		$_real_order_counter = 1;				
		foreach ($orders->rows as $_order){
			
			$this->objPHPExcel->getActiveSheet()->setCellValue('A'.$_order_counter, $_real_order_counter);
			$_total_weight = 0;
			
			$products = $this->model_sale_order->getOrderProducts($_order['order_id']);
			$_products_counter = 0;
			
			$_discount = '';
			$totals = $this->model_sale_order->getOrderTotals($_order['order_id']);
			foreach ($totals as $order_total){
					if (($order_total['value_national'] < 0) && $this->bool_real_stripos($order_total['title'], 'Скидка') && $this->bool_real_stripos($order_total['title'], '%')){
						$_discount = '; Скидка '.(int)preg_replace('~[^0-9]+~','', $order_total['title']).'%';
						break;
					}						
			}
			
			foreach ($products as $_product){
				$real_product = $this->model_catalog_product->getProduct($_product['product_id']);
				if ($real_product['short_name']){
					$_product['name'] = $real_product['short_name'];
				}
				
				$_product['name'] = str_replace('&amp;', '&', $_product['name']);
				
				$_telephone = $_order['telephone'];
				if ($_order['fax']){
					$_telephone = $_telephone . ' ' . $_order['fax'];
				}
				
				if ($real_product['weight'] && $real_product['weight_class_id']){							
					$_weight = $this->weight->convert($real_product['weight'], $real_product['weight_class_id'], 1);
					$_total_weight += $_weight;
				}
				
				if ($this->bool_real_stripos($_order['shipping_code'], 'pickup_advanced')){
					$_shipping_address = 'Самовывоз';
				} else {
					$_shipping_address = $_order['shipping_address_1'] . ' ' . $_order['shipping_address_2'];
				}
			
				$_row = $_order_counter+$_products_counter;
				$this->objPHPExcel->getActiveSheet()->setCellValue('B'.$_row, $_product['name']);
				$this->objPHPExcel->getActiveSheet()->setCellValueExplicit('C'.$_row, $_product['model'], PHPExcel_Cell_DataType::TYPE_STRING);
				$this->objPHPExcel->getActiveSheet()->setCellValue('D'.$_row, $_product['quantity']);
				$this->objPHPExcel->getActiveSheet()->setCellValue('E'.$_row, $_product['price_national']);
				$this->objPHPExcel->getActiveSheet()->setCellValue('F'.$_row, $_product['total_national']);
				$this->objPHPExcel->getActiveSheet()->setCellValueExplicit('G'.$_row, isset($real_product)?$real_product['ean']:'', PHPExcel_Cell_DataType::TYPE_STRING);
				$this->objPHPExcel->getActiveSheet()->setCellValue('H'.$_row, 'KP_' . $_order['order_id']);
				$this->objPHPExcel->getActiveSheet()->setCellValue('I'.$_row, $_order['shipping_firstname'] . ' ' . $_order['shipping_lastname']);
				$this->objPHPExcel->getActiveSheet()->setCellValue('J'.$_row, isset($_telephone)?$_telephone:'');
				$this->objPHPExcel->getActiveSheet()->setCellValue('K'.$_row, $_shipping_address);
				$this->objPHPExcel->getActiveSheet()->setCellValue('L'.$_row, $_order['shipping_city']);
				$this->objPHPExcel->getActiveSheet()->setCellValue('M'.$_row, date('d.m.Y', strtotime($_order['date_delivery_actual'])) . ' '.$_discount);
				$this->objPHPExcel->getActiveSheet()->setCellValue('O'.$_row, 'SUPERENGINE');
												
				
				$_products_counter++;
			}
			

			//Вес
			$this->objPHPExcel->getActiveSheet()->setCellValue('N'.$_order_counter, (int)$_total_weight);
			
			//has delivery
			$has_delivery = $this->db->query("SELECT * FROM `order_total` WHERE code='shipping' AND value_national > 0 AND order_id = '" . $_order['order_id'] . "'");
			if ($has_delivery->row){
				
				$_row = $_order_counter+$_products_counter;
				$this->objPHPExcel->getActiveSheet()->setCellValue('B'.$_row, 'Доставка');
				$this->objPHPExcel->getActiveSheet()->setCellValue('C'.$_row, 'б/а');
				$this->objPHPExcel->getActiveSheet()->setCellValue('D'.$_row, '1');
				$this->objPHPExcel->getActiveSheet()->setCellValue('E'.$_row, $has_delivery->row['value_national']);
				$this->objPHPExcel->getActiveSheet()->setCellValue('F'.$_row, $has_delivery->row['value_national']);
				$this->objPHPExcel->getActiveSheet()->setCellValueExplicit('G'.$_row, isset($real_product)?$real_product['ean']:'', PHPExcel_Cell_DataType::TYPE_STRING);
				$this->objPHPExcel->getActiveSheet()->setCellValue('H'.$_row, 'KP_' . $_order['order_id']);
				$this->objPHPExcel->getActiveSheet()->setCellValue('I'.$_row, $_order['shipping_firstname'] . ' ' . $_order['shipping_lastname']);
				$this->objPHPExcel->getActiveSheet()->setCellValue('J'.$_row, isset($_telephone)?$_telephone:'');
				$this->objPHPExcel->getActiveSheet()->setCellValue('K'.$_row, $_order['shipping_address_1'] . ' ' . $_order['shipping_address_2']);
				$this->objPHPExcel->getActiveSheet()->setCellValue('L'.$_row, $_order['shipping_city']);
				$this->objPHPExcel->getActiveSheet()->setCellValue('M'.$_row, date('d.m.Y', strtotime($_order['date_delivery_actual'])) .' '.$_discount);
				$this->objPHPExcel->getActiveSheet()->setCellValue('O'.$_row, 'SUPERENGINE');
				
				$_products_counter++;
				
			}
			
			$_order_counter += ($_products_counter+1);
			$_real_order_counter += 1;	
		}
		
		for ($i=1;$i<=$_order_counter;$i++){
			$this->objPHPExcel->getActiveSheet()->getStyle("A".$i.":O".$i)->getFont()->setSize(10);
		}
		
		
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
		
		
		if ($_country_id == 176){
			$this->getDownloadXlsFileOfMoscowDelivery($_date);
		} elseif ($_country_id == 20) {
			$this->getDownloadXlsFileOfMoscowDelivery($_date, 'Беларусь');
		}
		
	}
	

	private function createExcelWorksheet($order_id){

		// Loading language
		$this->load->language('report/export_xls');


		// Loading model
		$this->load->model('report/export_xls');
		$this->load->model('sale/order');


		// Get the the info relative to this order
		$result = $this->model_report_export_xls->getOrder($order_id);


		// Add it into an array
		foreach ($result as $res) {
			$this->data['orders'][] = array(
				'order_id' 				=> $res['order_id'],
				'store_name' 			=> $res['store_name'],
				'customer' 				=> $res['firstname'] . ' ' . $res['lastname'],
				'email'					=> $res['email'],
				'telephone'				=> $res['telephone'],
				'total'					=> $this->currency->format($res['total'], $res['currency_code'], $res['currency_value']),
				'date_added'			=> date($this->language->get('date_format_short'), strtotime($res['date_added'])),

				'currency_code'			=> $res['currency_code'],
				'currency_value'		=> $res['currency_value'],

				'shipping_firstname'	=> $res['shipping_firstname'],
				'shipping_lastname'		=> $res['shipping_lastname'],
				'shipping_address_1'	=> $res['shipping_address_1'],
				'shipping_address_2'	=> $res['shipping_address_2'],
				'shipping_city'			=> $res['shipping_city'],
				'shipping_postcode'		=> $res['shipping_postcode'],
				'shipping_zone'			=> $res['shipping_zone'],
				'shipping_country'		=> $res['shipping_country'],
				'shipping_method'		=> $res['shipping_method']
			);
		}


		// If the counter = 1, then we write the heading part (legend)
		if($this->mainCounter == 1){
			// Set document properties
			$this->objPHPExcel->getProperties()->setCreator("Baco")
										 	   ->setLastModifiedBy("Baco")
										 	   ->setTitle("Office 2007 XLSX")
										 	   ->setSubject("Office 2007 XLSX")
										 	   ->setDescription("Document for Office 2007 XLSX, generated by Baco from baco.pp.ua")
										 	   ->setKeywords("office 2007 excel")
										 	   ->setCategory("baco.pp.ua");


			// Create a first sheet, representing order data
			$this->objPHPExcel->setActiveSheetIndex(0);
		

			// Writing the heading part
			$this->objPHPExcel->getActiveSheet()->setCellValue('A' . $this->mainCounter, $this->language->get('header_order_id'));
			$this->objPHPExcel->getActiveSheet()->setCellValue('B' . $this->mainCounter, $this->language->get('header_store_name'));
			$this->objPHPExcel->getActiveSheet()->setCellValue('C' . $this->mainCounter, $this->language->get('header_customer'));
			$this->objPHPExcel->getActiveSheet()->setCellValue('D' . $this->mainCounter, $this->language->get('header_email'));
			$this->objPHPExcel->getActiveSheet()->setCellValue('E' . $this->mainCounter, $this->language->get('header_telephone'));
			$this->objPHPExcel->getActiveSheet()->setCellValue('F' . $this->mainCounter, $this->language->get('header_total'));
			$this->objPHPExcel->getActiveSheet()->setCellValue('G' . $this->mainCounter, $this->language->get('header_date'));
			$this->objPHPExcel->getActiveSheet()->setCellValue('H' . $this->mainCounter, $this->language->get('header_product_quantity'));
			$this->objPHPExcel->getActiveSheet()->setCellValue('I' . $this->mainCounter, $this->language->get('header_product_name'));
			$this->objPHPExcel->getActiveSheet()->setCellValue('J' . $this->mainCounter, $this->language->get('header_product_checkbox'));
			$this->objPHPExcel->getActiveSheet()->setCellValue('K' . $this->mainCounter, $this->language->get('header_product_select'));
			$this->objPHPExcel->getActiveSheet()->setCellValue('L' . $this->mainCounter, $this->language->get('header_product_radio'));
			$this->objPHPExcel->getActiveSheet()->setCellValue('M' . $this->mainCounter, $this->language->get('header_product_image'));
			$this->objPHPExcel->getActiveSheet()->setCellValue('N' . $this->mainCounter, $this->language->get('header_shipping_firstname'));
			$this->objPHPExcel->getActiveSheet()->setCellValue('O' . $this->mainCounter, $this->language->get('header_shipping_lastname'));
			$this->objPHPExcel->getActiveSheet()->setCellValue('P' . $this->mainCounter, $this->language->get('header_shipping_address'));
			$this->objPHPExcel->getActiveSheet()->setCellValue('Q' . $this->mainCounter, $this->language->get('header_shipping_city'));
			$this->objPHPExcel->getActiveSheet()->setCellValue('R' . $this->mainCounter, $this->language->get('header_shipping_postcode'));
			$this->objPHPExcel->getActiveSheet()->setCellValue('S' . $this->mainCounter, $this->language->get('header_shipping_zone'));
			$this->objPHPExcel->getActiveSheet()->setCellValue('T' . $this->mainCounter, $this->language->get('header_shipping_country'));
			$this->objPHPExcel->getActiveSheet()->setCellValue('U' . $this->mainCounter, $this->language->get('header_shipping_method'));
		}

		$product_data = array();
		// Get the associate products
		$products = $this->model_report_export_xls->getProductListFromOrder($order_id);
		// Set the counter just after the header line
		$counter  = $this->mainCounter+1;

	
		// Loop on all the product
		foreach ($products as $prod) {
			$option_data = array();

			$options = $this->model_sale_order->getOrderOptions($order_id, $prod['order_product_id']);

			if(!empty($options)){
				foreach ($options as $option) {
					if ($option['type'] == 'select') {
						$option_data['select'][] = array(
							'name'  => $option['name'],
							'value' => $option['value']
						);
					} 
					if ($option['type'] == 'checkbox') {
						$option_data['checkbox'][] = array(
							'name'  => $option['name'],
							'value' => $option['value']
						);
					}
					if ($option['type'] == 'radio') {
						$option_data['radio'][] = array(
							'name'  => $option['name'],
							'value' => $option['value']
						);
					}
					if ($option['type'] == 'image') {
						$option_data['image'][] = array(
							'name'  => $option['name'],
							'value' => $option['value']
						);
					}
				}
			}

			$checkbox = ''; $select = ''; $radio = ''; $image = '';
			if( !empty($option_data['checkbox']) ){
				//$checkbox = implode(":",array_values($option_data['checkbox'][0]));
				$values = array_map('array_pop', $option_data['checkbox']);
				$checkbox = implode(';', $values);
			}
			if( !empty($option_data['select']) ){
				$select = implode(":",array_values($option_data['select'][0]));
			}
			if( !empty($option_data['radio']) ){
				$radio = implode(":",array_values($option_data['radio'][0]));
			}
			if( !empty($option_data['image']) ){
				$image = implode(":",array_values($option_data['image'][0]));
			}

			//$buf = print_r($checkbox ,true);$this->log->write($buf);
			
			
			
			// Add each product line
			$this->objPHPExcel->getActiveSheet()->setCellValue('A' . $counter, $this->data['orders'][0]['order_id']);
			$this->objPHPExcel->getActiveSheet()->setCellValue('B' . $counter, $this->data['orders'][0]['store_name']);
			$this->objPHPExcel->getActiveSheet()->setCellValue('C' . $counter, $this->data['orders'][0]['customer']);
			$this->objPHPExcel->getActiveSheet()->setCellValue('D' . $counter, $this->data['orders'][0]['email']);
			$this->objPHPExcel->getActiveSheet()->setCellValue('E' . $counter, $this->data['orders'][0]['telephone']);
			$this->objPHPExcel->getActiveSheet()->setCellValue('F' . $counter, $this->data['orders'][0]['total']);
			$this->objPHPExcel->getActiveSheet()->setCellValue('G' . $counter, $this->data['orders'][0]['date_added']);
			$this->objPHPExcel->getActiveSheet()->setCellValue('H' . $counter, $prod['quantity']);
			$this->objPHPExcel->getActiveSheet()->setCellValue('I' . $counter, html_entity_decode($prod['name'], ENT_QUOTES, 'UTF-8'));
			$this->objPHPExcel->getActiveSheet()->setCellValue('J' . $counter, $checkbox);
			$this->objPHPExcel->getActiveSheet()->setCellValue('K' . $counter, $select);
			$this->objPHPExcel->getActiveSheet()->setCellValue('L' . $counter, $radio);
			$this->objPHPExcel->getActiveSheet()->setCellValue('M' . $counter, $image);
			$this->objPHPExcel->getActiveSheet()->setCellValue('N' . $counter, $this->data['orders'][0]['shipping_firstname']);
			$this->objPHPExcel->getActiveSheet()->setCellValue('O' . $counter, $this->data['orders'][0]['shipping_lastname']);
			$this->objPHPExcel->getActiveSheet()->setCellValue('P' . $counter, $this->data['orders'][0]['shipping_address_1'] . ' ' . 	$this->data['orders'][0]['shipping_address_2']);
			$this->objPHPExcel->getActiveSheet()->setCellValue('Q' . $counter, $this->data['orders'][0]['shipping_city']);
			$this->objPHPExcel->getActiveSheet()->setCellValue('R' . $counter, $this->data['orders'][0]['shipping_postcode']);
			$this->objPHPExcel->getActiveSheet()->setCellValue('S' . $counter, $this->data['orders'][0]['shipping_zone']);
			$this->objPHPExcel->getActiveSheet()->setCellValue('T' . $counter, $this->data['orders'][0]['shipping_country']);
			$this->objPHPExcel->getActiveSheet()->setCellValue('U' . $counter, $this->data['orders'][0]['shipping_method']);

			$counter++;
			$this->mainCounter++;
		}


		// Set thin black border outline around column
		$styleThinBlackBorderOutline = array(
			'borders' => array(
				'outline' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN,
					'color' => array('argb' => 'FF000000'),
				),
			),
		);


		// Set the style of heading cells
		$this->objPHPExcel->getActiveSheet()->getStyle('A1:S1')->applyFromArray($styleThinBlackBorderOutline);
		$this->objPHPExcel->getActiveSheet()->getStyle('A1:S1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$this->objPHPExcel->getActiveSheet()->getStyle('A1:S1')->getFill()->getStartColor()->setARGB('FF808080');


		// Set column widths
		$columns = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U');


		// General style
		foreach ($columns as $col) {
			$this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
			$this->objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setSize(11);
			$this->objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setBold(false);
			$this->objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLACK);
			$this->objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setShrinkToFit(true);
		}


		unset($this->data['orders']);
	}
}
<?php
ini_set("memory_limit","256M");

	$this->objPHPExcel = new PHPExcel();
	$this->objPHPExcel->getActiveSheet()->setTitle('Sales Orders Report');
	$this->mainCounter = 1;
	if ($this->mainCounter == 1) {
		 $this->objPHPExcel->getProperties()->setCreator("ADV Reports & Statistics")
										 	->setLastModifiedBy("ADV Reports & Statistics")
										 	->setTitle("ADV Sales Orders Report")
										 	->setSubject("ADV Sales Orders Report")
										 	->setDescription("Export of ADV Sales Profit Report with all details.")
										 	->setKeywords("office 2007 excel")
										 	->setCategory("www.opencartreports.com");
											   
		 $this->objPHPExcel->setActiveSheetIndex(0);	

		 $this->objPHPExcel->getActiveSheet()->setCellValue('A' . $this->mainCounter, $this->language->get('column_order_order_id'));
		 $this->objPHPExcel->getActiveSheet()->getStyle('A' . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getStyle('A' . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		 
		 $this->objPHPExcel->getActiveSheet()->setCellValue('B' . $this->mainCounter, $this->language->get('column_order_date_added'));
		 $this->objPHPExcel->getActiveSheet()->getStyle('B' . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);	
		  
		 $this->objPHPExcel->getActiveSheet()->setCellValue('C' . $this->mainCounter, $this->language->get('column_order_inv_no'));
		 $this->objPHPExcel->getActiveSheet()->getStyle('C' . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);	
		 
		 $this->objPHPExcel->getActiveSheet()->setCellValue('D' . $this->mainCounter, $this->language->get('column_order_customer_name'));
		 $this->objPHPExcel->getActiveSheet()->getStyle('D' . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
		 
		 $this->objPHPExcel->getActiveSheet()->setCellValue('E' . $this->mainCounter, $this->language->get('column_order_email'));
		 $this->objPHPExcel->getActiveSheet()->getStyle('E' . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
		 
		 $this->objPHPExcel->getActiveSheet()->setCellValue('F' . $this->mainCounter, $this->language->get('column_order_customer_group'));
		 $this->objPHPExcel->getActiveSheet()->getStyle('F' . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
		 
		 $this->objPHPExcel->getActiveSheet()->setCellValue('G' . $this->mainCounter, $this->language->get('column_prod_id'));
		 $this->objPHPExcel->getActiveSheet()->getStyle('G' . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getStyle('G' . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
		 
		 $this->objPHPExcel->getActiveSheet()->setCellValue('H' . $this->mainCounter, $this->language->get('column_prod_sku'));
		 $this->objPHPExcel->getActiveSheet()->getStyle('H' . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
		 
		 $this->objPHPExcel->getActiveSheet()->setCellValue('I' . $this->mainCounter, $this->language->get('column_prod_model'));
		 $this->objPHPExcel->getActiveSheet()->getStyle('I' . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
		 
		 $this->objPHPExcel->getActiveSheet()->setCellValue('J' . $this->mainCounter, $this->language->get('column_prod_name'));
		 $this->objPHPExcel->getActiveSheet()->getStyle('J' . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
		 
		 $this->objPHPExcel->getActiveSheet()->setCellValue('K' . $this->mainCounter, $this->language->get('column_prod_option'));
		 $this->objPHPExcel->getActiveSheet()->getStyle('K' . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
		 
		 $this->objPHPExcel->getActiveSheet()->setCellValue('L' . $this->mainCounter, $this->language->get('column_prod_attributes'));
		 $this->objPHPExcel->getActiveSheet()->getStyle('L' . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
		 
		 $this->objPHPExcel->getActiveSheet()->setCellValue('M' . $this->mainCounter, $this->language->get('column_prod_manu'));
		 $this->objPHPExcel->getActiveSheet()->getStyle('M' . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
		 
		 $this->objPHPExcel->getActiveSheet()->setCellValue('N' . $this->mainCounter, $this->language->get('column_prod_category'));
		 $this->objPHPExcel->getActiveSheet()->getStyle('N' . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
		 
		 $this->objPHPExcel->getActiveSheet()->setCellValue('O' . $this->mainCounter, $this->language->get('column_prod_currency'));
		 $this->objPHPExcel->getActiveSheet()->getStyle('O' . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
		 
		 $this->objPHPExcel->getActiveSheet()->setCellValue('P' . $this->mainCounter, $this->language->get('column_prod_price'));
		 $this->objPHPExcel->getActiveSheet()->getStyle('P' . $this->mainCounter)->getFont()->setBold(true);	
		 $this->objPHPExcel->getActiveSheet()->getStyle('P' . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
		 
		 $this->objPHPExcel->getActiveSheet()->setCellValue('Q' . $this->mainCounter, $this->language->get('column_prod_quantity'));
		 $this->objPHPExcel->getActiveSheet()->getStyle('Q' . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getStyle('Q' . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);

		 $this->objPHPExcel->getActiveSheet()->setCellValue('R' . $this->mainCounter, $this->language->get('column_prod_total_excl_vat'));
		 $this->objPHPExcel->getActiveSheet()->getStyle('R' . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getStyle('R' . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
		 
		 $this->objPHPExcel->getActiveSheet()->setCellValue('S' . $this->mainCounter, $this->language->get('column_prod_tax'));
		 $this->objPHPExcel->getActiveSheet()->getStyle('S' . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getStyle('S' . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);

		 $this->objPHPExcel->getActiveSheet()->setCellValue('T' . $this->mainCounter, $this->language->get('column_prod_total_incl_vat'));
		 $this->objPHPExcel->getActiveSheet()->getStyle('T' . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getStyle('T' . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);		 
		 
		 $this->objPHPExcel->getActiveSheet()->setCellValue('U' . $this->mainCounter, $this->language->get('column_sub_total'));
		 $this->objPHPExcel->getActiveSheet()->getStyle('U' . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getStyle('U' . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension('U')->setAutoSize(true);	
		 
		 $this->objPHPExcel->getActiveSheet()->setCellValue('V' . $this->mainCounter, $this->language->get('column_handling'));
		 $this->objPHPExcel->getActiveSheet()->getStyle('V' . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getStyle('V' . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension('V')->setAutoSize(true);	
		 
		 $this->objPHPExcel->getActiveSheet()->setCellValue('W' . $this->mainCounter, $this->language->get('column_loworder'));
		 $this->objPHPExcel->getActiveSheet()->getStyle('W' . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getStyle('W' . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension('W')->setAutoSize(true);	
		 
		 $this->objPHPExcel->getActiveSheet()->setCellValue('X' . $this->mainCounter, $this->language->get('column_shipping'));
		 $this->objPHPExcel->getActiveSheet()->getStyle('X' . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getStyle('X' . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension('X')->setAutoSize(true);	
		 
		 $this->objPHPExcel->getActiveSheet()->setCellValue('Y' . $this->mainCounter, $this->language->get('column_reward'));
		 $this->objPHPExcel->getActiveSheet()->getStyle('Y' . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getStyle('Y' . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension('Y')->setAutoSize(true);	
		 
		 $this->objPHPExcel->getActiveSheet()->setCellValue('Z' . $this->mainCounter, $this->language->get('column_coupon'));
		 $this->objPHPExcel->getActiveSheet()->getStyle('Z' . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getStyle('Z' . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension('Z')->setAutoSize(true);	
		 
		 $this->objPHPExcel->getActiveSheet()->setCellValue('AA' . $this->mainCounter, $this->language->get('column_coupon_code'));
		 $this->objPHPExcel->getActiveSheet()->getStyle('AA' . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension('AA')->setAutoSize(true);	
		 
		 $this->objPHPExcel->getActiveSheet()->setCellValue('AB' . $this->mainCounter, $this->language->get('column_order_tax'));
		 $this->objPHPExcel->getActiveSheet()->getStyle('AB' . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getStyle('AB' . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension('AB')->setAutoSize(true);	
		 
		 $this->objPHPExcel->getActiveSheet()->setCellValue('AC' . $this->mainCounter, $this->language->get('column_credit'));
		 $this->objPHPExcel->getActiveSheet()->getStyle('AC' . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getStyle('AC' . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension('AC')->setAutoSize(true);	
		 
		 $this->objPHPExcel->getActiveSheet()->setCellValue('AD' . $this->mainCounter, $this->language->get('column_voucher'));
		 $this->objPHPExcel->getActiveSheet()->getStyle('AD' . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getStyle('AD' . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension('AD')->setAutoSize(true);	
		 
		 $this->objPHPExcel->getActiveSheet()->setCellValue('AE' . $this->mainCounter, $this->language->get('column_voucher_code'));
		 $this->objPHPExcel->getActiveSheet()->getStyle('AE' . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension('AE')->setAutoSize(true);	

		 $this->objPHPExcel->getActiveSheet()->setCellValue('AF' . $this->mainCounter, $this->language->get('column_commission'));
		 $this->objPHPExcel->getActiveSheet()->getStyle('AF' . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getStyle('AF' . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension('AF')->setAutoSize(true);	
		 
		 $this->objPHPExcel->getActiveSheet()->setCellValue('AG' . $this->mainCounter, $this->language->get('column_order_value'));
		 $this->objPHPExcel->getActiveSheet()->getStyle('AG' . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getStyle('AG' . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension('AG')->setAutoSize(true);	
		 
		 $this->objPHPExcel->getActiveSheet()->setCellValue('AH' . $this->mainCounter, $this->language->get('column_order_shipping_method'));
		 $this->objPHPExcel->getActiveSheet()->getStyle('AH' . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension('AH')->setAutoSize(true);	
		 
		 $this->objPHPExcel->getActiveSheet()->setCellValue('AI' . $this->mainCounter, $this->language->get('column_order_payment_method'));
		 $this->objPHPExcel->getActiveSheet()->getStyle('AI' . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension('AI')->setAutoSize(true);	
		 
		 $this->objPHPExcel->getActiveSheet()->setCellValue('AJ' . $this->mainCounter, $this->language->get('column_order_status'));
		 $this->objPHPExcel->getActiveSheet()->getStyle('AJ' . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension('AJ')->setAutoSize(true);	
		 
		 $this->objPHPExcel->getActiveSheet()->setCellValue('AK' . $this->mainCounter, $this->language->get('column_order_store'));
		 $this->objPHPExcel->getActiveSheet()->getStyle('AK' . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension('AK')->setAutoSize(true);	
		 
		 $this->objPHPExcel->getActiveSheet()->setCellValue('AL' . $this->mainCounter, $this->language->get('column_customer_cust_id'));
		 $this->objPHPExcel->getActiveSheet()->getStyle('AL' . $this->mainCounter)->getFont()->setBold(true);	
		 $this->objPHPExcel->getActiveSheet()->getStyle('AL' . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension('AL')->setAutoSize(true);	
		 
		 $this->objPHPExcel->getActiveSheet()->setCellValue('AM' . $this->mainCounter, strip_tags($this->language->get('column_billing_name')));
		 $this->objPHPExcel->getActiveSheet()->getStyle('AM' . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension('AM')->setAutoSize(true);	
		 
		 $this->objPHPExcel->getActiveSheet()->setCellValue('AN' . $this->mainCounter, strip_tags($this->language->get('column_billing_company')));
		 $this->objPHPExcel->getActiveSheet()->getStyle('AN' . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension('AN')->setAutoSize(true);	
		 
		 $this->objPHPExcel->getActiveSheet()->setCellValue('AO' . $this->mainCounter, strip_tags($this->language->get('column_billing_address_1')));
		 $this->objPHPExcel->getActiveSheet()->getStyle('AO' . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension('AO')->setAutoSize(true);	
		 
		 $this->objPHPExcel->getActiveSheet()->setCellValue('AP' . $this->mainCounter, strip_tags($this->language->get('column_billing_address_2')));
		 $this->objPHPExcel->getActiveSheet()->getStyle('AP' . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension('AP')->setAutoSize(true);	
		 
		 $this->objPHPExcel->getActiveSheet()->setCellValue('AQ' . $this->mainCounter, strip_tags($this->language->get('column_billing_city')));
		 $this->objPHPExcel->getActiveSheet()->getStyle('AQ' . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension('AQ')->setAutoSize(true);	
		 
		 $this->objPHPExcel->getActiveSheet()->setCellValue('AR' . $this->mainCounter, strip_tags($this->language->get('column_billing_zone')));
		 $this->objPHPExcel->getActiveSheet()->getStyle('AR' . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension('AR')->setAutoSize(true);	
		 
		 $this->objPHPExcel->getActiveSheet()->setCellValue('AS' . $this->mainCounter, strip_tags($this->language->get('column_billing_postcode')));
		 $this->objPHPExcel->getActiveSheet()->getStyle('AS' . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension('AS')->setAutoSize(true);	
		 
		 $this->objPHPExcel->getActiveSheet()->setCellValue('AT' . $this->mainCounter, strip_tags($this->language->get('column_billing_country')));
		 $this->objPHPExcel->getActiveSheet()->getStyle('AT' . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension('AT')->setAutoSize(true);	
		 
		 $this->objPHPExcel->getActiveSheet()->setCellValue('AU' . $this->mainCounter, $this->language->get('column_customer_telephone'));
		 $this->objPHPExcel->getActiveSheet()->getStyle('AU' . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension('AU')->setAutoSize(true);	
		 
		 $this->objPHPExcel->getActiveSheet()->setCellValue('AV' . $this->mainCounter, strip_tags($this->language->get('column_shipping_name')));
		 $this->objPHPExcel->getActiveSheet()->getStyle('AV' . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension('AV')->setAutoSize(true);	
		 
		 $this->objPHPExcel->getActiveSheet()->setCellValue('AW' . $this->mainCounter, strip_tags($this->language->get('column_shipping_company')));
		 $this->objPHPExcel->getActiveSheet()->getStyle('AW' . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension('AW')->setAutoSize(true);	
		 
		 $this->objPHPExcel->getActiveSheet()->setCellValue('AX' . $this->mainCounter, strip_tags($this->language->get('column_shipping_address_1')));
		 $this->objPHPExcel->getActiveSheet()->getStyle('AX' . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension('AX')->setAutoSize(true);	
		 
		 $this->objPHPExcel->getActiveSheet()->setCellValue('AY' . $this->mainCounter, strip_tags($this->language->get('column_shipping_address_2')));
		 $this->objPHPExcel->getActiveSheet()->getStyle('AY' . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension('AY')->setAutoSize(true);	
		 
		 $this->objPHPExcel->getActiveSheet()->setCellValue('AZ' . $this->mainCounter, strip_tags($this->language->get('column_shipping_city')));
		 $this->objPHPExcel->getActiveSheet()->getStyle('AZ' . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension('AZ')->setAutoSize(true);	
		 
		 $this->objPHPExcel->getActiveSheet()->setCellValue('BA' . $this->mainCounter, strip_tags($this->language->get('column_shipping_zone')));
		 $this->objPHPExcel->getActiveSheet()->getStyle('BA' . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension('BA')->setAutoSize(true);	
		 
		 $this->objPHPExcel->getActiveSheet()->setCellValue('BB' . $this->mainCounter, strip_tags($this->language->get('column_shipping_postcode')));
		 $this->objPHPExcel->getActiveSheet()->getStyle('BB' . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension('BB')->setAutoSize(true);	
		 
		 $this->objPHPExcel->getActiveSheet()->setCellValue('BC' . $this->mainCounter, strip_tags($this->language->get('column_shipping_country')));
		 $this->objPHPExcel->getActiveSheet()->getStyle('BC' . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension('BC')->setAutoSize(true);	
		 
		 $this->objPHPExcel->getActiveSheet()->setCellValue('BD' . $this->mainCounter, $this->language->get('column_order_comment'));
		 $this->objPHPExcel->getActiveSheet()->getStyle('BD' . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension('BD')->setAutoSize(true);			 
		 
 		 $this->objPHPExcel->getActiveSheet()->freezePane('B2');
	}
	
	$counter  = $this->mainCounter+1;
		
		foreach ($rows as $row) {
		
		$this->objPHPExcel->getActiveSheet()->setCellValue('A' . $counter, $row['order_id']);
		
		$this->objPHPExcel->getActiveSheet()->setCellValue('B' . $counter, date($this->language->get('date_format_short'), strtotime($row['date_added'])));
		
		$this->objPHPExcel->getActiveSheet()->setCellValue('C' . $counter, $row['invoice_prefix'] . $row['invoice_no']);
		 
		$this->objPHPExcel->getActiveSheet()->setCellValue('D' . $counter, $row['firstname'] . ' ' . $row['lastname']);
		
		$this->objPHPExcel->getActiveSheet()->setCellValue('E' . $counter, $row['email']);
		
		$this->objPHPExcel->getActiveSheet()->setCellValue('F' . $counter, $row['order_group']);
		
		$this->objPHPExcel->getActiveSheet()->setCellValue('G' . $counter, $row['product_id']);
		
		$this->objPHPExcel->getActiveSheet()->getStyle('H' . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
		$this->objPHPExcel->getActiveSheet()->setCellValue('H' . $counter, $row['product_sku']);
		
		$this->objPHPExcel->getActiveSheet()->getStyle('I' . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);		
		$this->objPHPExcel->getActiveSheet()->setCellValue('I' . $counter, $row['product_model']);
		
		$this->objPHPExcel->getActiveSheet()->setCellValue('J' . $counter, html_entity_decode($row['product_name']));
		
		$this->objPHPExcel->getActiveSheet()->setCellValue('K' . $counter, html_entity_decode($row['product_option']));

		$this->objPHPExcel->getActiveSheet()->setCellValue('L' . $counter, html_entity_decode($row['product_attributes']));
		
		$this->objPHPExcel->getActiveSheet()->setCellValue('M' . $counter, html_entity_decode($row['product_manu']));
		
		$this->objPHPExcel->getActiveSheet()->setCellValue('N' . $counter, html_entity_decode($row['product_category']));		

		$this->objPHPExcel->getActiveSheet()->setCellValue('O' . $counter, $row['currency_code']);
		
		$this->objPHPExcel->getActiveSheet()->getStyle('P' . $counter)->getNumberFormat()->setFormatCode('0.00');
		$this->objPHPExcel->getActiveSheet()->setCellValue('P' . $counter, $row['product_price']);
		
		$this->objPHPExcel->getActiveSheet()->setCellValue('Q' . $counter, $row['product_quantity']);

		$this->objPHPExcel->getActiveSheet()->getStyle('R' . $counter)->getNumberFormat()->setFormatCode('0.00');
		$this->objPHPExcel->getActiveSheet()->setCellValue('R' . $counter, $row['product_total_excl_vat']);
		
		$this->objPHPExcel->getActiveSheet()->getStyle('S' . $counter)->getNumberFormat()->setFormatCode('0.00');
		$this->objPHPExcel->getActiveSheet()->setCellValue('S' . $counter, $row['product_tax']);

		$this->objPHPExcel->getActiveSheet()->getStyle('T' . $counter)->getNumberFormat()->setFormatCode('0.00');
		$this->objPHPExcel->getActiveSheet()->setCellValue('T' . $counter, $row['product_total_incl_vat']);

		$this->objPHPExcel->getActiveSheet()->getStyle('U' . $counter)->getNumberFormat()->setFormatCode('0.00');
		$this->objPHPExcel->getActiveSheet()->setCellValue('U' . $counter, $row['order_sub_total']);
		
		$this->objPHPExcel->getActiveSheet()->getStyle('V' . $counter)->getNumberFormat()->setFormatCode('0.00');
		$this->objPHPExcel->getActiveSheet()->setCellValue('V' . $counter, $row['order_handling'] != NULL ? $row['order_handling'] : '0.00');
		
		$this->objPHPExcel->getActiveSheet()->getStyle('W' . $counter)->getNumberFormat()->setFormatCode('0.00');
		$this->objPHPExcel->getActiveSheet()->setCellValue('W' . $counter, $row['order_low_order_fee'] != NULL ? $row['order_low_order_fee'] : '0.00');
		
		$this->objPHPExcel->getActiveSheet()->getStyle('X' . $counter)->getNumberFormat()->setFormatCode('0.00');
		$this->objPHPExcel->getActiveSheet()->setCellValue('X' . $counter, $row['order_shipping'] != NULL ? $row['order_shipping'] : '0.00');
		
		$this->objPHPExcel->getActiveSheet()->getStyle('Y' . $counter)->getNumberFormat()->setFormatCode('0.00');
		$this->objPHPExcel->getActiveSheet()->setCellValue('Y' . $counter, $row['order_reward'] != NULL ? $row['order_reward'] : '0.00');
		
		$this->objPHPExcel->getActiveSheet()->getStyle('Z' . $counter)->getNumberFormat()->setFormatCode('0.00');
		$this->objPHPExcel->getActiveSheet()->setCellValue('Z' . $counter, $row['order_coupon'] != NULL ? $row['order_coupon'] : '0.00');

		$this->objPHPExcel->getActiveSheet()->setCellValue('AA' . $counter, $row['order_coupon_code']);
		
		$this->objPHPExcel->getActiveSheet()->getStyle('AB' . $counter)->getNumberFormat()->setFormatCode('0.00');
		$this->objPHPExcel->getActiveSheet()->setCellValue('AB' . $counter, $row['order_tax'] != NULL ? $row['order_tax'] : '0.00');
		
		$this->objPHPExcel->getActiveSheet()->getStyle('AC' . $counter)->getNumberFormat()->setFormatCode('0.00');
		$this->objPHPExcel->getActiveSheet()->setCellValue('AC' . $counter, $row['order_credit'] != NULL ? $row['order_credit'] : '0.00');
		
		$this->objPHPExcel->getActiveSheet()->getStyle('AD' . $counter)->getNumberFormat()->setFormatCode('0.00');
		$this->objPHPExcel->getActiveSheet()->setCellValue('AD' . $counter, $row['order_voucher'] != NULL ? $row['order_voucher'] : '0.00');

		$this->objPHPExcel->getActiveSheet()->setCellValue('AE' . $counter, $row['order_voucher_code']);

		$this->objPHPExcel->getActiveSheet()->getStyle('AF' . $counter)->getNumberFormat()->setFormatCode('0.00');
		$this->objPHPExcel->getActiveSheet()->setCellValue('AF' . $counter, -$row['order_commission']);
		
		$this->objPHPExcel->getActiveSheet()->getStyle('AG' . $counter)->getNumberFormat()->setFormatCode('0.00');
		$this->objPHPExcel->getActiveSheet()->setCellValue('AG' . $counter, $row['order_value']);

		$this->objPHPExcel->getActiveSheet()->setCellValue('AH' . $counter, strip_tags($row['shipping_method']));
		
		$this->objPHPExcel->getActiveSheet()->setCellValue('AI' . $counter, strip_tags($row['payment_method']));
		
		$this->objPHPExcel->getActiveSheet()->setCellValue('AJ' . $counter, $row['order_status']);
		
		$this->objPHPExcel->getActiveSheet()->setCellValue('AK' . $counter, html_entity_decode($row['store_name']));
		
		$this->objPHPExcel->getActiveSheet()->setCellValue('AL' . $counter, $row['customer_id']);
		
		$this->objPHPExcel->getActiveSheet()->setCellValue('AM' . $counter, $row['payment_firstname'] . ' ' . $row['payment_lastname']);
		
		$this->objPHPExcel->getActiveSheet()->setCellValue('AN' . $counter, $row['payment_company']);
		
		$this->objPHPExcel->getActiveSheet()->setCellValue('AO' . $counter, $row['payment_address_1']);
		
		$this->objPHPExcel->getActiveSheet()->setCellValue('AP' . $counter, $row['payment_address_2']);
		
		$this->objPHPExcel->getActiveSheet()->setCellValue('AQ' . $counter, $row['payment_city']);
		
		$this->objPHPExcel->getActiveSheet()->setCellValue('AR' . $counter, $row['payment_zone']);
		
		$this->objPHPExcel->getActiveSheet()->getStyle('AS' . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);		
		$this->objPHPExcel->getActiveSheet()->setCellValue('AS' . $counter, $row['payment_postcode']);

		$this->objPHPExcel->getActiveSheet()->setCellValue('AT' . $counter, $row['payment_country']);
		
		$this->objPHPExcel->getActiveSheet()->getStyle('AU' . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);		
		$this->objPHPExcel->getActiveSheet()->setCellValue('AU' . $counter, $row['telephone']);

		$this->objPHPExcel->getActiveSheet()->setCellValue('AV' . $counter, $row['shipping_firstname'] . ' ' . $row['shipping_lastname']);
		
		$this->objPHPExcel->getActiveSheet()->setCellValue('AW' . $counter, $row['shipping_company']);
		
		$this->objPHPExcel->getActiveSheet()->setCellValue('AX' . $counter, $row['shipping_address_1']);
		
		$this->objPHPExcel->getActiveSheet()->setCellValue('AY' . $counter, $row['shipping_address_2']);
		
		$this->objPHPExcel->getActiveSheet()->setCellValue('AZ' . $counter, $row['shipping_city']);
		
		$this->objPHPExcel->getActiveSheet()->setCellValue('BA' . $counter, $row['shipping_zone']);
		
		$this->objPHPExcel->getActiveSheet()->getStyle('BB' . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);			
		$this->objPHPExcel->getActiveSheet()->setCellValue('BB' . $counter, $row['shipping_postcode']);

		$this->objPHPExcel->getActiveSheet()->setCellValue('BC' . $counter, $row['shipping_country']);
		
		$this->objPHPExcel->getActiveSheet()->setCellValue('BD' . $counter, html_entity_decode($row['comment']));
		
		$counter++;
		$this->mainCounter++;
	}

	$lastRow = $this->objPHPExcel->getActiveSheet()->getHighestRow();
	
	if (!isset($_POST['so1064'])) {
		$this->objPHPExcel->getActiveSheet()->removeColumn('BD');
	}

	if (!isset($_POST['so1061'])) {
		$this->objPHPExcel->getActiveSheet()->removeColumn('BC');
	}
	
	if (!isset($_POST['so1060'])) {
		$this->objPHPExcel->getActiveSheet()->removeColumn('BB');	
	}
	
	if (!isset($_POST['so1059'])) {
		$this->objPHPExcel->getActiveSheet()->removeColumn('BA');
	}
	
	if (!isset($_POST['so1058'])) {
		$this->objPHPExcel->getActiveSheet()->removeColumn('AZ');
	}
	
	if (!isset($_POST['so1057'])) {
		$this->objPHPExcel->getActiveSheet()->removeColumn('AY');	
	}
	
	if (!isset($_POST['so1056'])) {
		$this->objPHPExcel->getActiveSheet()->removeColumn('AX');
	}
	
	if (!isset($_POST['so1055'])) {
		$this->objPHPExcel->getActiveSheet()->removeColumn('AW');	
	}
	
	if (!isset($_POST['so1054'])) {
		$this->objPHPExcel->getActiveSheet()->removeColumn('AV');
	}
	
	if (!isset($_POST['so1053'])) {
		$this->objPHPExcel->getActiveSheet()->removeColumn('AU');
	}
	
	if (!isset($_POST['so1052'])) {
		$this->objPHPExcel->getActiveSheet()->removeColumn('AT');	
	}
	
	if (!isset($_POST['so1051'])) {
		$this->objPHPExcel->getActiveSheet()->removeColumn('AS');	
	}
	
	if (!isset($_POST['so1050'])) {
		$this->objPHPExcel->getActiveSheet()->removeColumn('AR');	
	}
	
	if (!isset($_POST['so1049'])) {
		$this->objPHPExcel->getActiveSheet()->removeColumn('AQ');	
	}
	
	if (!isset($_POST['so1048'])) {
		$this->objPHPExcel->getActiveSheet()->removeColumn('AP');	
	}	

	if (!isset($_POST['so1047'])) {
		$this->objPHPExcel->getActiveSheet()->removeColumn('AO');	
	}
	
	if (!isset($_POST['so1046'])) {
		$this->objPHPExcel->getActiveSheet()->removeColumn('AN');	
	}

	if (!isset($_POST['so1045'])) {
		$this->objPHPExcel->getActiveSheet()->removeColumn('AM');	
	}
	
	if (!isset($_POST['so1044'])) {
		$this->objPHPExcel->getActiveSheet()->removeColumn('AL');	
	}
	
	if (!isset($_POST['so1043'])) {
		$this->objPHPExcel->getActiveSheet()->removeColumn('AK');	
	}
	
	if (!isset($_POST['so1042'])) {
		$this->objPHPExcel->getActiveSheet()->removeColumn('AJ');	
	}
	
	if (!isset($_POST['so1041'])) {
		$this->objPHPExcel->getActiveSheet()->removeColumn('AI');	
	}
	
	if (!isset($_POST['so1040'])) {
		$this->objPHPExcel->getActiveSheet()->removeColumn('AH');	
	}
	
	if (!isset($_POST['so1031'])) {
		$this->objPHPExcel->getActiveSheet()->removeColumn('AG');	
	}

	if (!isset($_POST['so1034'])) {
		$this->objPHPExcel->getActiveSheet()->removeColumn('AF');	
	}
	
	if (!isset($_POST['so1030'])) {
		$this->objPHPExcel->getActiveSheet()->removeColumn('AE');	
	}
	
	if (!isset($_POST['so1029'])) {
		$this->objPHPExcel->getActiveSheet()->removeColumn('AD');	
	}
	
	if (!isset($_POST['so1028'])) {
		$this->objPHPExcel->getActiveSheet()->removeColumn('AC');	
	}
	
	if (!isset($_POST['so1027'])) {
		$this->objPHPExcel->getActiveSheet()->removeColumn('AB');	
	}
	
	if (!isset($_POST['so1026'])) {
		$this->objPHPExcel->getActiveSheet()->removeColumn('AA');	
	}
	
	if (!isset($_POST['so1025'])) {
		$this->objPHPExcel->getActiveSheet()->removeColumn('Z');	
	}
	
	if (!isset($_POST['so1024'])) {
		$this->objPHPExcel->getActiveSheet()->removeColumn('Y');	
	}
	
	if (!isset($_POST['so1023'])) {
		$this->objPHPExcel->getActiveSheet()->removeColumn('X');	
	}
	
	if (!isset($_POST['so1022'])) {
		$this->objPHPExcel->getActiveSheet()->removeColumn('W');	
	}
	
	if (!isset($_POST['so1021'])) {
		$this->objPHPExcel->getActiveSheet()->removeColumn('V');	
	}
	
	if (!isset($_POST['so1020'])) {
		$this->objPHPExcel->getActiveSheet()->removeColumn('U');	
	}

	if (!isset($_POST['so1016b'])) {
		$this->objPHPExcel->getActiveSheet()->removeColumn('T');	
	}

	if (!isset($_POST['so1015'])) {
		$this->objPHPExcel->getActiveSheet()->removeColumn('S');	
	}
	
	if (!isset($_POST['so1016a'])) {
		$this->objPHPExcel->getActiveSheet()->removeColumn('R');	
	}
	
	if (!isset($_POST['so1014'])) {
		$this->objPHPExcel->getActiveSheet()->removeColumn('Q');	
	}
	
	if (!isset($_POST['so1013'])) {
		$this->objPHPExcel->getActiveSheet()->removeColumn('P');	
	}
	
	if (!isset($_POST['so1012'])) {
		$this->objPHPExcel->getActiveSheet()->removeColumn('O');	
	}
	
	if (!isset($_POST['so1011'])) {
		$this->objPHPExcel->getActiveSheet()->removeColumn('N');	
	}
	
	if (!isset($_POST['so1010'])) {
		$this->objPHPExcel->getActiveSheet()->removeColumn('M');	
	}
	
	if (!isset($_POST['so1009'])) {
		$this->objPHPExcel->getActiveSheet()->removeColumn('L');	
	}
	
	if (!isset($_POST['so1008'])) {
		$this->objPHPExcel->getActiveSheet()->removeColumn('K');	
	}
	
	if (!isset($_POST['so1007'])) {
		$this->objPHPExcel->getActiveSheet()->removeColumn('J');	
	}
	
	if (!isset($_POST['so1006'])) {
		$this->objPHPExcel->getActiveSheet()->removeColumn('I');	
	}
	
	if (!isset($_POST['so1005'])) {
		$this->objPHPExcel->getActiveSheet()->removeColumn('H');	
	}
	
	if (!isset($_POST['so1004'])) {
		$this->objPHPExcel->getActiveSheet()->removeColumn('G');	
	}
	
	if (!isset($_POST['so1003'])) {
		$this->objPHPExcel->getActiveSheet()->removeColumn('F');	
	}
	
	if (!isset($_POST['so1002'])) {
		$this->objPHPExcel->getActiveSheet()->removeColumn('E');	
	}
	
	if (!isset($_POST['so1001'])) {
		$this->objPHPExcel->getActiveSheet()->removeColumn('D');	
	}
	
	if (!isset($_POST['so1000'])) {
		$this->objPHPExcel->getActiveSheet()->removeColumn('C');	
	}

	if (!isset($_POST['so1064'])) {
		$lastCellA = $this->objPHPExcel->getActiveSheet()->getHighestDataColumn();	
		$lastCellB = $this->objPHPExcel->getActiveSheet()->getHighestDataRow();
		$this->objPHPExcel->getActiveSheet()->getCellCacheController()->deleteCacheData($lastCellA . $lastCellB);
	}
	
$filename = "sale_order_report_all_details_".date("Y-m-d",time());
header('Expires: 0');
header('Cache-control: private');
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=UTF-8; encoding=UTF-8');
header('Content-Disposition: attachment;filename='.$filename.".xlsx");
header('Content-Transfer-Encoding: UTF-8');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($this->objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit();
?>
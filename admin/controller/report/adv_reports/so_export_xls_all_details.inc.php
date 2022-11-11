<?php
ini_set("memory_limit","256M");

		// we use our own error handler
		global $config;
		global $log;
		$config = $this->config;
		$log = $this->log;
		set_error_handler('error_handler_for_export',E_ALL);
		register_shutdown_function('fatal_error_shutdown_handler_for_export');
		
		// Creating a workbook
		$workbook = new Spreadsheet_Excel_Writer();
		$workbook->setTempDir(DIR_CACHE);
		$workbook->setVersion(8); // Use Excel97/2000 BIFF8 Format

		// Formating a workbook
		$textFormat =& $workbook->addFormat(array('Align' => 'left', 'NumFormat' => "@"));
		
		$numberFormat =& $workbook->addFormat(array('Align' => 'left'));	

		$priceFormat =& $workbook->addFormat(array('Align' => 'right'));
		$priceFormat->setNumFormat('0.00');
		
		$boxFormatText =& $workbook->addFormat(array('bold' => 1));
		$boxFormatNumber =& $workbook->addFormat(array('Align' => 'right', 'bold' => 1));
		
		// sending HTTP headers
		$workbook->send('sale_order_report_all_details_'.date("Y-m-d",time()).'.xls');
		
		$worksheet =& $workbook->addWorksheet('Sales Orders Report');
		$worksheet->setInputEncoding('UTF-8');
		$worksheet->setZoom(90);

		// Set the column widths
		$j = 0;
		$worksheet->setColumn($j,$j++,10); // A
		$worksheet->setColumn($j,$j++,13); // B
		isset($_POST['so1000']) ? $worksheet->setColumn($j,$j++,15) : ''; // C
		isset($_POST['so1001']) ? $worksheet->setColumn($j,$j++,20) : ''; // D
		isset($_POST['so1002']) ? $worksheet->setColumn($j,$j++,20) : ''; // E
		isset($_POST['so1003']) ? $worksheet->setColumn($j,$j++,15) : ''; // F
		isset($_POST['so1004']) ? $worksheet->setColumn($j,$j++,10) : ''; // G
		isset($_POST['so1005']) ? $worksheet->setColumn($j,$j++,13) : ''; // H
		isset($_POST['so1006']) ? $worksheet->setColumn($j,$j++,13) : ''; // I
		isset($_POST['so1007']) ? $worksheet->setColumn($j,$j++,25) : ''; // J
		isset($_POST['so1008']) ? $worksheet->setColumn($j,$j++,20) : ''; // K
		isset($_POST['so1009']) ? $worksheet->setColumn($j,$j++,20) : ''; // L
		isset($_POST['so1010']) ? $worksheet->setColumn($j,$j++,20) : ''; // M
		isset($_POST['so1011']) ? $worksheet->setColumn($j,$j++,20) : ''; // N
		isset($_POST['so1012']) ? $worksheet->setColumn($j,$j++,10) : ''; // O
		isset($_POST['so1013']) ? $worksheet->setColumn($j,$j++,13) : ''; // P
		isset($_POST['so1014']) ? $worksheet->setColumn($j,$j++,15) : ''; // Q
		isset($_POST['so1016a']) ? $worksheet->setColumn($j,$j++,13) : ''; // R
		isset($_POST['so1015']) ? $worksheet->setColumn($j,$j++,13) : ''; // S	
		isset($_POST['so1016b']) ? $worksheet->setColumn($j,$j++,13) : ''; // T		
		isset($_POST['so1020']) ? $worksheet->setColumn($j,$j++,13) : ''; // U
		isset($_POST['so1021']) ? $worksheet->setColumn($j,$j++,13) : ''; // V
		isset($_POST['so1022']) ? $worksheet->setColumn($j,$j++,13) : ''; // W
		isset($_POST['so1023']) ? $worksheet->setColumn($j,$j++,13) : ''; // X
		isset($_POST['so1024']) ? $worksheet->setColumn($j,$j++,13) : ''; // Y
		isset($_POST['so1025']) ? $worksheet->setColumn($j,$j++,13) : ''; // Z
		isset($_POST['so1026']) ? $worksheet->setColumn($j,$j++,19) : ''; // AA
		isset($_POST['so1027']) ? $worksheet->setColumn($j,$j++,13) : ''; // AB
		isset($_POST['so1028']) ? $worksheet->setColumn($j,$j++,13) : ''; // AC
		isset($_POST['so1029']) ? $worksheet->setColumn($j,$j++,13) : ''; // AD
		isset($_POST['so1030']) ? $worksheet->setColumn($j,$j++,15) : ''; // AE
		isset($_POST['so1034']) ? $worksheet->setColumn($j,$j++,19) : ''; // AF		
		isset($_POST['so1031']) ? $worksheet->setColumn($j,$j++,13) : ''; // AG
		isset($_POST['so1040']) ? $worksheet->setColumn($j,$j++,18) : ''; // AH
		isset($_POST['so1041']) ? $worksheet->setColumn($j,$j++,18) : ''; // AI
		isset($_POST['so1042']) ? $worksheet->setColumn($j,$j++,13) : ''; // AJ
		isset($_POST['so1043']) ? $worksheet->setColumn($j,$j++,18) : ''; // AK
		isset($_POST['so1044']) ? $worksheet->setColumn($j,$j++,11) : ''; // AL
		isset($_POST['so1045']) ? $worksheet->setColumn($j,$j++,20) : ''; // AM
		isset($_POST['so1046']) ? $worksheet->setColumn($j,$j++,20) : ''; // AN
		isset($_POST['so1047']) ? $worksheet->setColumn($j,$j++,20) : ''; // AO
		isset($_POST['so1048']) ? $worksheet->setColumn($j,$j++,20) : ''; // AP
		isset($_POST['so1049']) ? $worksheet->setColumn($j,$j++,20) : ''; // AQ
		isset($_POST['so1050']) ? $worksheet->setColumn($j,$j++,21) : ''; // AR
		isset($_POST['so1051']) ? $worksheet->setColumn($j,$j++,17) : ''; // AS
		isset($_POST['so1052']) ? $worksheet->setColumn($j,$j++,20) : ''; // AT
		isset($_POST['so1053']) ? $worksheet->setColumn($j,$j++,15) : ''; // AU
		isset($_POST['so1054']) ? $worksheet->setColumn($j,$j++,20) : ''; // AV
		isset($_POST['so1055']) ? $worksheet->setColumn($j,$j++,20) : ''; // AW
		isset($_POST['so1056']) ? $worksheet->setColumn($j,$j++,20) : ''; // AX
		isset($_POST['so1057']) ? $worksheet->setColumn($j,$j++,20) : ''; // BY
		isset($_POST['so1058']) ? $worksheet->setColumn($j,$j++,20) : ''; // BZ
		isset($_POST['so1059']) ? $worksheet->setColumn($j,$j++,21) : ''; // BA
		isset($_POST['so1060']) ? $worksheet->setColumn($j,$j++,17) : ''; // BB
		isset($_POST['so1061']) ? $worksheet->setColumn($j,$j++,20) : ''; // BC
		isset($_POST['so1064']) ? $worksheet->setColumn($j,$j++,15) : ''; // BD
		
		// The order headings row
		$i = 0;
		$j = 0;	
		$worksheet->writeString($i, $j++, $this->language->get('column_order_order_id'), $boxFormatText); // A
		$worksheet->writeString($i, $j++, $this->language->get('column_order_date_added'), $boxFormatText); // B
		isset($_POST['so1000']) ? $worksheet->writeString($i, $j++, $this->language->get('column_order_inv_no'), $boxFormatText) : ''; // C
		isset($_POST['so1001']) ? $worksheet->writeString($i, $j++, $this->language->get('column_order_customer_name'), $boxFormatText) : ''; // D
		isset($_POST['so1002']) ? $worksheet->writeString($i, $j++, $this->language->get('column_order_email'), $boxFormatText) : ''; // E
		isset($_POST['so1003']) ? $worksheet->writeString($i, $j++, $this->language->get('column_order_customer_group'), $boxFormatText) : ''; // F
		isset($_POST['so1004']) ? $worksheet->writeString($i, $j++, $this->language->get('column_prod_id'), $boxFormatText) : ''; // G
		isset($_POST['so1005']) ? $worksheet->writeString($i, $j++, $this->language->get('column_prod_sku'), $boxFormatText) : ''; // H
		isset($_POST['so1006']) ? $worksheet->writeString($i, $j++, $this->language->get('column_prod_model'), $boxFormatText) : ''; // I
		isset($_POST['so1007']) ? $worksheet->writeString($i, $j++, $this->language->get('column_prod_name'), $boxFormatText) : ''; // J
		isset($_POST['so1008']) ? $worksheet->writeString($i, $j++, $this->language->get('column_prod_option'), $boxFormatText) : ''; // K
		isset($_POST['so1009']) ? $worksheet->writeString($i, $j++, $this->language->get('column_prod_attributes'), $boxFormatText) : ''; // L
		isset($_POST['so1010']) ? $worksheet->writeString($i, $j++, $this->language->get('column_prod_manu'), $boxFormatText) : ''; // M
		isset($_POST['so1011']) ? $worksheet->writeString($i, $j++, $this->language->get('column_prod_category'), $boxFormatText) : ''; // N
		isset($_POST['so1012']) ? $worksheet->writeString($i, $j++, $this->language->get('column_prod_currency'), $boxFormatText) : ''; // O
		isset($_POST['so1013']) ? $worksheet->writeString($i, $j++, $this->language->get('column_prod_price'), $boxFormatNumber) : ''; // P
		isset($_POST['so1014']) ? $worksheet->writeString($i, $j++, $this->language->get('column_prod_quantity'), $boxFormatNumber) : ''; // Q
		isset($_POST['so1016a']) ? $worksheet->writeString($i, $j++, $this->language->get('column_prod_total_excl_vat'), $boxFormatNumber) : ''; // R		
		isset($_POST['so1015']) ? $worksheet->writeString($i, $j++, $this->language->get('column_prod_tax'), $boxFormatNumber) : ''; // S
		isset($_POST['so1016b']) ? $worksheet->writeString($i, $j++, $this->language->get('column_prod_total_incl_vat'), $boxFormatNumber) : ''; // T
		isset($_POST['so1020']) ? $worksheet->writeString($i, $j++, $this->language->get('column_sub_total'), $boxFormatNumber) : ''; // U
		isset($_POST['so1021']) ? $worksheet->writeString($i, $j++, $this->language->get('column_handling'), $boxFormatNumber) : ''; // V
		isset($_POST['so1022']) ? $worksheet->writeString($i, $j++, $this->language->get('column_loworder'), $boxFormatNumber) : ''; // W
		isset($_POST['so1023']) ? $worksheet->writeString($i, $j++, $this->language->get('column_shipping'), $boxFormatNumber) : ''; // X
		isset($_POST['so1024']) ? $worksheet->writeString($i, $j++, $this->language->get('column_reward'), $boxFormatNumber) : ''; // Y
		isset($_POST['so1025']) ? $worksheet->writeString($i, $j++, $this->language->get('column_coupon'), $boxFormatNumber) : ''; // Z
		isset($_POST['so1026']) ? $worksheet->writeString($i, $j++, $this->language->get('column_coupon_code'), $boxFormatText) : ''; // AA
		isset($_POST['so1027']) ? $worksheet->writeString($i, $j++, $this->language->get('column_order_tax'), $boxFormatNumber) : ''; // AB
		isset($_POST['so1028']) ? $worksheet->writeString($i, $j++, $this->language->get('column_credit'), $boxFormatNumber) : ''; // AC
		isset($_POST['so1029']) ? $worksheet->writeString($i, $j++, $this->language->get('column_voucher'), $boxFormatNumber) : ''; // AD
		isset($_POST['so1030']) ? $worksheet->writeString($i, $j++, $this->language->get('column_voucher_code'), $boxFormatText) : ''; // AE
		isset($_POST['so1034']) ? $worksheet->writeString($i, $j++, $this->language->get('column_commission'), $boxFormatNumber) : ''; // AF		
		isset($_POST['so1031']) ? $worksheet->writeString($i, $j++, $this->language->get('column_order_value'), $boxFormatNumber) : ''; // AG
		isset($_POST['so1040']) ? $worksheet->writeString($i, $j++, $this->language->get('column_order_shipping_method'), $boxFormatText) : ''; // AH
		isset($_POST['so1041']) ? $worksheet->writeString($i, $j++, $this->language->get('column_order_payment_method'), $boxFormatText) : ''; // AI
		isset($_POST['so1042']) ? $worksheet->writeString($i, $j++, $this->language->get('column_order_status'), $boxFormatText) : ''; // AJ
		isset($_POST['so1043']) ? $worksheet->writeString($i, $j++, $this->language->get('column_order_store'), $boxFormatText) : ''; // AK
		isset($_POST['so1044']) ? $worksheet->writeString($i, $j++, $this->language->get('column_customer_cust_id'), $boxFormatText) : ''; // AL
		isset($_POST['so1045']) ? $worksheet->writeString($i, $j++, strip_tags($this->language->get('column_billing_name')), $boxFormatText) : ''; // AM
		isset($_POST['so1046']) ? $worksheet->writeString($i, $j++, strip_tags($this->language->get('column_billing_company')), $boxFormatText) : ''; // AN
		isset($_POST['so1047']) ? $worksheet->writeString($i, $j++, strip_tags($this->language->get('column_billing_address_1')), $boxFormatText) : ''; // AO
		isset($_POST['so1048']) ? $worksheet->writeString($i, $j++, strip_tags($this->language->get('column_billing_address_2')), $boxFormatText) : ''; // AP
		isset($_POST['so1049']) ? $worksheet->writeString($i, $j++, strip_tags($this->language->get('column_billing_city')), $boxFormatText) : ''; // AQ
		isset($_POST['so1050']) ? $worksheet->writeString($i, $j++, strip_tags($this->language->get('column_billing_zone')), $boxFormatText) : ''; // AR
		isset($_POST['so1051']) ? $worksheet->writeString($i, $j++, strip_tags($this->language->get('column_billing_postcode')), $boxFormatText) : ''; // AS
		isset($_POST['so1052']) ? $worksheet->writeString($i, $j++, strip_tags($this->language->get('column_billing_country')), $boxFormatText) : ''; // AT
		isset($_POST['so1053']) ? $worksheet->writeString($i, $j++, $this->language->get('column_customer_telephone'), $boxFormatText) : ''; // AU
		isset($_POST['so1054']) ? $worksheet->writeString($i, $j++, strip_tags($this->language->get('column_shipping_name')), $boxFormatText) : ''; // AV
		isset($_POST['so1055']) ? $worksheet->writeString($i, $j++, strip_tags($this->language->get('column_shipping_company')), $boxFormatText) : ''; // AW
		isset($_POST['so1056']) ? $worksheet->writeString($i, $j++, strip_tags($this->language->get('column_shipping_address_1')), $boxFormatText) : ''; // AX
		isset($_POST['so1057']) ? $worksheet->writeString($i, $j++, strip_tags($this->language->get('column_shipping_address_2')), $boxFormatText) : ''; // AY
		isset($_POST['so1058']) ? $worksheet->writeString($i, $j++, strip_tags($this->language->get('column_shipping_city')), $boxFormatText) : ''; // AZ
		isset($_POST['so1059']) ? $worksheet->writeString($i, $j++, strip_tags($this->language->get('column_shipping_zone')), $boxFormatText) : ''; // BA
		isset($_POST['so1060']) ? $worksheet->writeString($i, $j++, strip_tags($this->language->get('column_shipping_postcode')), $boxFormatText) : ''; // BB
		isset($_POST['so1061']) ? $worksheet->writeString($i, $j++, strip_tags($this->language->get('column_shipping_country')), $boxFormatText) : ''; // BC
		isset($_POST['so1064']) ? $worksheet->writeString($i, $j++, $this->language->get('column_order_comment'), $boxFormatText) : ''; // BD

		// The actual orders data
		$i += 1;
		$j = 0;
		
			foreach ($rows as $row) {
		
			$excelRow = $i+1;			
				$worksheet->write($i, $j++, $row['order_id'], $numberFormat); // A
				$worksheet->write($i, $j++, date($this->language->get('date_format_short'), strtotime($row['date_added'])), $textFormat); // B
				isset($_POST['so1000']) ? $worksheet->write($i, $j++, $row['invoice_prefix'] . $row['invoice_no'], $textFormat) : ''; // C
				isset($_POST['so1001']) ? $worksheet->write($i, $j++, $row['firstname'] . ' ' . $row['lastname'], $textFormat) : ''; // D
				isset($_POST['so1002']) ? $worksheet->write($i, $j++, $row['email'], $textFormat) : ''; // E
				isset($_POST['so1003']) ? $worksheet->write($i, $j++, $row['order_group'], $textFormat) : ''; // F
				isset($_POST['so1004']) ? $worksheet->write($i, $j++, $row['product_id'], $numberFormat) : ''; // G
				isset($_POST['so1005']) ? $worksheet->write($i, $j++, $row['product_sku'], $textFormat) : ''; // H
				isset($_POST['so1006']) ? $worksheet->write($i, $j++, $row['product_model'], $textFormat) : ''; // I
				isset($_POST['so1007']) ? $worksheet->write($i, $j++, html_entity_decode($row['product_name']), $textFormat) : ''; // J
				isset($_POST['so1008']) ? $worksheet->write($i, $j++, html_entity_decode($row['product_option']), $textFormat) : ''; // K
				isset($_POST['so1009']) ? $worksheet->write($i, $j++, html_entity_decode($row['product_attributes']), $textFormat) : ''; // L
				isset($_POST['so1010']) ? $worksheet->write($i, $j++, html_entity_decode($row['product_manu']), $textFormat) : ''; // M
				isset($_POST['so1011']) ? $worksheet->write($i, $j++, html_entity_decode($row['product_category']), $textFormat) : ''; // N
				isset($_POST['so1012']) ? $worksheet->write($i, $j++, $row['currency_code'], $textFormat) : ''; // O
				isset($_POST['so1013']) ? $worksheet->write($i, $j++, $row['product_price'], $priceFormat) : ''; // P
				isset($_POST['so1014']) ? $worksheet->write($i, $j++, $row['product_quantity']) : ''; // Q
				isset($_POST['so1016a']) ? $worksheet->write($i, $j++, $row['product_total_excl_vat'], $priceFormat) : ''; // R
				isset($_POST['so1015']) ? $worksheet->write($i, $j++, $row['product_tax'], $priceFormat) : ''; // S
				isset($_POST['so1016b']) ? $worksheet->write($i, $j++, $row['product_total_incl_vat'], $priceFormat) : ''; // T
				isset($_POST['so1020']) ? $worksheet->write($i, $j++, $row['order_sub_total'], $priceFormat) : ''; // U
				isset($_POST['so1021']) ? $worksheet->write($i, $j++, $row['order_handling'] != NULL ? $row['order_handling'] : '0.00', $priceFormat) : ''; // V
				isset($_POST['so1022']) ? $worksheet->write($i, $j++, $row['order_low_order_fee'] != NULL ? $row['order_low_order_fee'] : '0.00', $priceFormat) : ''; // W
				isset($_POST['so1023']) ? $worksheet->write($i, $j++, $row['order_shipping'] != NULL ? $row['order_shipping'] : '0.00', $priceFormat) : ''; // X
				isset($_POST['so1024']) ? $worksheet->write($i, $j++, $row['order_reward'] != NULL ? $row['order_reward'] : '0.00', $priceFormat) : ''; // Y
				isset($_POST['so1025']) ? $worksheet->write($i, $j++, $row['order_coupon'] != NULL ? $row['order_coupon'] : '0.00', $priceFormat) : ''; // Z
				isset($_POST['so1026']) ? $worksheet->write($i, $j++, $row['order_coupon_code'], $textFormat) : ''; // AA
				isset($_POST['so1027']) ? $worksheet->write($i, $j++, $row['order_tax'] != NULL ? $row['order_tax'] : '0.00', $priceFormat) : ''; // AB
				isset($_POST['so1028']) ? $worksheet->write($i, $j++, $row['order_credit'] != NULL ? $row['order_credit'] : '0.00', $priceFormat) : ''; // AC
				isset($_POST['so1029']) ? $worksheet->write($i, $j++, $row['order_voucher'] != NULL ? $row['order_voucher'] : '0.00', $priceFormat) : ''; // AD
				isset($_POST['so1030']) ? $worksheet->write($i, $j++, $row['order_voucher_code'], $textFormat) : ''; // AE
				isset($_POST['so1034']) ? $worksheet->write($i, $j++, -$row['order_commission'], $priceFormat) : ''; // AF				
				isset($_POST['so1031']) ? $worksheet->write($i, $j++, $row['order_value'], $priceFormat) : ''; // AG
				isset($_POST['so1040']) ? $worksheet->write($i, $j++, strip_tags($row['shipping_method']), $textFormat) : ''; // AH
				isset($_POST['so1041']) ? $worksheet->write($i, $j++, strip_tags($row['payment_method']), $textFormat) : ''; // AI
				isset($_POST['so1042']) ? $worksheet->write($i, $j++, $row['order_status'], $textFormat) : ''; // AJ
				isset($_POST['so1043']) ? $worksheet->write($i, $j++, html_entity_decode($row['store_name']), $textFormat) : ''; // AK
				isset($_POST['so1044']) ? $worksheet->write($i, $j++, $row['customer_id'], $numberFormat) : ''; // AL
				isset($_POST['so1045']) ? $worksheet->write($i, $j++, $row['payment_firstname'] . ' ' . $row['payment_lastname'], $textFormat) : ''; // AM
				isset($_POST['so1046']) ? $worksheet->write($i, $j++, $row['payment_company'], $textFormat) : ''; // AN
				isset($_POST['so1047']) ? $worksheet->write($i, $j++, $row['payment_address_1'], $textFormat) : ''; // AO
				isset($_POST['so1048']) ? $worksheet->write($i, $j++, $row['payment_address_2'], $textFormat) : ''; // AP
				isset($_POST['so1049']) ? $worksheet->write($i, $j++, $row['payment_city'], $textFormat) : ''; // AQ
				isset($_POST['so1050']) ? $worksheet->write($i, $j++, $row['payment_zone'], $textFormat) : ''; // AR
				isset($_POST['so1051']) ? $worksheet->write($i, $j++, $row['payment_postcode'], $textFormat) : ''; // AS
				isset($_POST['so1052']) ? $worksheet->write($i, $j++, $row['payment_country'], $textFormat) : ''; // AT
				isset($_POST['so1053']) ? $worksheet->write($i, $j++, $row['telephone'], $textFormat) : ''; // AU
				isset($_POST['so1054']) ? $worksheet->write($i, $j++, $row['shipping_firstname'] . ' ' . $row['shipping_lastname'], $textFormat) : ''; // AV
				isset($_POST['so1055']) ? $worksheet->write($i, $j++, $row['shipping_company'], $textFormat) : ''; // AW
				isset($_POST['so1056']) ? $worksheet->write($i, $j++, $row['shipping_address_1'], $textFormat) : ''; // AX
				isset($_POST['so1057']) ? $worksheet->write($i, $j++, $row['shipping_address_2'], $textFormat) : ''; // AY
				isset($_POST['so1058']) ? $worksheet->write($i, $j++, $row['shipping_city'], $textFormat) : ''; // AZ
				isset($_POST['so1059']) ? $worksheet->write($i, $j++, $row['shipping_zone'], $textFormat) : ''; // BA
				isset($_POST['so1060']) ? $worksheet->write($i, $j++, $row['shipping_postcode'], $textFormat) : ''; // BB
				isset($_POST['so1061']) ? $worksheet->write($i, $j++, $row['shipping_country'], $textFormat) : ''; // BC
				isset($_POST['so1064']) ? $worksheet->write($i, $j++, html_entity_decode($row['comment']), $textFormat) : ''; // BD

				$i += 1;
				$j = 0;
			}
		
		$worksheet->freezePanes(array(1, 1, 1, 1));
		
		// Let's send the file		
		$workbook->close();
		
		// Clear the spreadsheet caches
		$this->clearSpreadsheetCache();
		exit;
?>
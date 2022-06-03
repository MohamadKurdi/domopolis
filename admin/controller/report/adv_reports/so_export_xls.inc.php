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
		$workbook->send('sale_order_report_'.date("Y-m-d",time()).'.xls');
		
		$worksheet =& $workbook->addWorksheet('Sales Orders Report');
		$worksheet->setInputEncoding('UTF-8');
		$worksheet->setZoom(90);

		// Set the column widths
		$j = 0;
		if ($filter_report == 'sales_summary') {
		if ($filter_group == 'year') {		
		$worksheet->setMerge(0, 1, 0, 1);
		$worksheet->setColumn($j,$j++,10); // A,B
		} elseif ($filter_group == 'quarter') {
		$worksheet->setColumn($j,$j++,10); // A
		$worksheet->setColumn($j,$j++,10); // B			
		} elseif ($filter_group == 'month') {
		$worksheet->setColumn($j,$j++,10); // A
		$worksheet->setColumn($j,$j++,13); // B
		} elseif ($filter_group == 'day') {
		$worksheet->setMerge(0, 1, 0, 1);
		$worksheet->setColumn($j,$j++,13); // A,B
		} elseif ($filter_group == 'order') {
		$worksheet->setColumn($j,$j++,10); // A
		$worksheet->setColumn($j,$j++,13); // B
		} else {
		$worksheet->setColumn($j,$j++,13); // A
		$worksheet->setColumn($j,$j++,13); // B
		}
		} elseif ($filter_report == 'day_of_week') {
		$worksheet->setMerge(0, 1, 0, 1);
		$worksheet->setColumn($j,$j++,15); // A,B
		} elseif ($filter_report == 'hour') {
		$worksheet->setMerge(0, 1, 0, 1);
		$worksheet->setColumn($j,$j++,10); // A,B
		} elseif ($filter_report == 'store') {
		$worksheet->setMerge(0, 1, 0, 1);
		$worksheet->setColumn($j,$j++,20); // A,B
		} elseif ($filter_report == 'customer_group') {
		$worksheet->setMerge(0, 1, 0, 1);
		$worksheet->setColumn($j,$j++,15); // A,B
		} elseif ($filter_report == 'country') {
		$worksheet->setMerge(0, 1, 0, 1);
		$worksheet->setColumn($j,$j++,15); // A,B
		} elseif ($filter_report == 'postcode') {
		$worksheet->setMerge(0, 1, 0, 1);
		$worksheet->setColumn($j,$j++,18); // A,B
		} elseif ($filter_report == 'region_state') {
		$worksheet->setMerge(0, 1, 0, 1);
		$worksheet->setColumn($j,$j++,25); // A,B
		} elseif ($filter_report == 'city') {
		$worksheet->setMerge(0, 1, 0, 1);
		$worksheet->setColumn($j,$j++,25); // A,B
		} elseif ($filter_report == 'payment_method') {
		$worksheet->setMerge(0, 1, 0, 1);
		$worksheet->setColumn($j,$j++,20); // A,B
		} elseif ($filter_report == 'shipping_method') {
		$worksheet->setMerge(0, 1, 0, 1);
		$worksheet->setColumn($j,$j++,20); // A,B	
		}			
		isset($_POST['so20']) ? $worksheet->setColumn($j,$j++,10) : ''; // C
		isset($_POST['so21']) ? $worksheet->setColumn($j,$j++,10) : ''; // D
		isset($_POST['so22']) ? $worksheet->setColumn($j,$j++,10) : ''; // E
		isset($_POST['so23']) ? $worksheet->setColumn($j,$j++,13) : ''; // F
		isset($_POST['so24']) ? $worksheet->setColumn($j,$j++,15) : ''; // G
		isset($_POST['so25']) ? $worksheet->setColumn($j,$j++,15) : ''; // H
		isset($_POST['so27']) ? $worksheet->setColumn($j,$j++,13) : ''; // I
		isset($_POST['so26']) ? $worksheet->setColumn($j,$j++,15) : ''; // J
		isset($_POST['so28']) ? $worksheet->setColumn($j,$j++,13) : ''; // K
		isset($_POST['so29']) ? $worksheet->setColumn($j,$j++,13) : ''; // L
		isset($_POST['so30']) ? $worksheet->setColumn($j,$j++,15) : ''; // M
		isset($_POST['so31']) ? $worksheet->setColumn($j,$j++,15) : ''; // N
		isset($_POST['so32']) ? $worksheet->setColumn($j,$j++,20) : ''; // O		
		isset($_POST['so33']) ? $worksheet->setColumn($j,$j++,15) : ''; // P
		
		// The order headings row
		$i = 0;
		$j = 0;	
		if ($filter_report == 'sales_summary') {
		if ($filter_group == 'year') {		
		$worksheet->writeString($i, $j++, $this->language->get('column_year'), $boxFormatText); // A,B
		} elseif ($filter_group == 'quarter') {
		$worksheet->writeString($i, $j++, $this->language->get('column_year'), $boxFormatText); // A
		$worksheet->writeString($i, $j++, $this->language->get('column_quarter'), $boxFormatText); // B		
		} elseif ($filter_group == 'month') {
		$worksheet->writeString($i, $j++, $this->language->get('column_year'), $boxFormatText); // A
		$worksheet->writeString($i, $j++, $this->language->get('column_month'), $boxFormatText); // B
		} elseif ($filter_group == 'day') {
		$worksheet->writeString($i, $j++, $this->language->get('column_date'), $boxFormatText); // A,B
		} elseif ($filter_group == 'order') {
		$worksheet->writeString($i, $j++, $this->language->get('column_order_order_id'), $boxFormatText); // A
		$worksheet->writeString($i, $j++, $this->language->get('column_order_date_added'), $boxFormatText); // B
		} else {
		$worksheet->writeString($i, $j++, $this->language->get('column_date_start'), $boxFormatText); // A
		$worksheet->writeString($i, $j++, $this->language->get('column_date_end'), $boxFormatText); // B
		}
		} elseif ($filter_report == 'day_of_week') {
		$worksheet->writeString($i, $j++, $this->language->get('column_day_of_week'), $boxFormatText); // A,B
		} elseif ($filter_report == 'hour') {
		$worksheet->writeString($i, $j++, $this->language->get('column_hour'), $boxFormatText); // A,B
		} elseif ($filter_report == 'store') {
		$worksheet->writeString($i, $j++, $this->language->get('column_store'), $boxFormatText); // A,B
		} elseif ($filter_report == 'customer_group') {
		$worksheet->writeString($i, $j++, $this->language->get('column_customer_group'), $boxFormatText); // A,B
		} elseif ($filter_report == 'country') {
		$worksheet->writeString($i, $j++, $this->language->get('column_country'), $boxFormatText); // A,B
		} elseif ($filter_report == 'postcode') {
		$worksheet->writeString($i, $j++, $this->language->get('column_postcode'), $boxFormatText); // A,B
		} elseif ($filter_report == 'region_state') {
		$worksheet->writeString($i, $j++, $this->language->get('column_region_state'), $boxFormatText); // A,B
		} elseif ($filter_report == 'city') {
		$worksheet->writeString($i, $j++, $this->language->get('column_city'), $boxFormatText); // A,B
		} elseif ($filter_report == 'payment_method') {
		$worksheet->writeString($i, $j++, $this->language->get('column_payment_method'), $boxFormatText); // A,B
		} elseif ($filter_report == 'shipping_method') {
		$worksheet->writeString($i, $j++, $this->language->get('column_shipping_method'), $boxFormatText); // A,B	
		}			
		isset($_POST['so20']) ? $worksheet->writeString($i, $j++, $this->language->get('column_orders'), $boxFormatNumber) : ''; // C
		isset($_POST['so21']) ? $worksheet->writeString($i, $j++, $this->language->get('column_customers'), $boxFormatNumber) : ''; // D
		isset($_POST['so22']) ? $worksheet->writeString($i, $j++, $this->language->get('column_products'), $boxFormatNumber) : ''; // E
		isset($_POST['so23']) ? $worksheet->writeString($i, $j++, $this->language->get('column_sub_total'), $boxFormatNumber) : ''; // F
		isset($_POST['so24']) ? $worksheet->writeString($i, $j++, $this->language->get('column_handling'), $boxFormatNumber) : ''; // G
		isset($_POST['so25']) ? $worksheet->writeString($i, $j++, $this->language->get('column_loworder'), $boxFormatNumber) : ''; // H
		isset($_POST['so27']) ? $worksheet->writeString($i, $j++, $this->language->get('column_shipping'), $boxFormatNumber) : ''; // I
		isset($_POST['so26']) ? $worksheet->writeString($i, $j++, $this->language->get('column_reward'), $boxFormatNumber) : ''; // J
		isset($_POST['so28']) ? $worksheet->writeString($i, $j++, $this->language->get('column_coupon'), $boxFormatNumber) : ''; // K
		isset($_POST['so29']) ? $worksheet->writeString($i, $j++, $this->language->get('column_tax'), $boxFormatNumber) : ''; // L		
		isset($_POST['so30']) ? $worksheet->writeString($i, $j++, $this->language->get('column_credit'), $boxFormatNumber) : ''; // M
		isset($_POST['so31']) ? $worksheet->writeString($i, $j++, $this->language->get('column_voucher'), $boxFormatNumber) : ''; // N
		isset($_POST['so32']) ? $worksheet->writeString($i, $j++, $this->language->get('column_commission'), $boxFormatNumber) : ''; // O
		isset($_POST['so33']) ? $worksheet->writeString($i, $j++, $this->language->get('column_total'), $boxFormatNumber) : ''; // P
		
		// The actual orders data
		$i += 1;
		$j = 0;
		
			foreach ($results as $result) {
		
			$excelRow = $i+1;
				if ($filter_report == 'sales_summary') {
				if ($filter_group == 'year') {		
				$worksheet->write($i, $j++, $result['year'], $textFormat); // A,B
				} elseif ($filter_group == 'quarter') {
				$worksheet->write($i, $j++, $result['year'], $textFormat); // A
				$worksheet->write($i, $j++, 'Q' . $result['quarter'], $textFormat); // B				
				} elseif ($filter_group == 'month') {
				$worksheet->write($i, $j++, $result['year'], $textFormat); // A
				$worksheet->write($i, $j++, $result['month'], $textFormat); // B
				} elseif ($filter_group == 'day') {
				$worksheet->write($i, $j++, date($this->language->get('date_format_short'), strtotime($result['date_start'])), $textFormat); // A,B
				} elseif ($filter_group == 'order') {
				$worksheet->write($i, $j++, $result['order_id'], $textFormat); // A
				$worksheet->write($i, $j++, date($this->language->get('date_format_short'), strtotime($result['date_start'])), $textFormat); // B
				} else {
				$worksheet->write($i, $j++, date($this->language->get('date_format_short'), strtotime($result['date_start'])), $textFormat); // A
				$worksheet->write($i, $j++, date($this->language->get('date_format_short'), strtotime($result['date_end'])), $textFormat); // B	
				}	
				} elseif ($filter_report == 'day_of_week') {
				$worksheet->write($i, $j++, $result['day_of_week'], $textFormat); // A,B
				} elseif ($filter_report == 'hour') {
				$worksheet->write($i, $j++, number_format($result['hour'], 2, ':', ''), $textFormat); // A,B
				} elseif ($filter_report == 'store') {
				$worksheet->write($i, $j++, html_entity_decode($result['store_name']), $textFormat); // A,B
				} elseif ($filter_report == 'customer_group') {
				$worksheet->write($i, $j++, html_entity_decode($result['customer_group']), $textFormat); // A,B
				} elseif ($filter_report == 'country') {
				$worksheet->write($i, $j++, $result['payment_country'], $textFormat); // A,B
				} elseif ($filter_report == 'postcode') {
				$worksheet->write($i, $j++, $result['payment_postcode'], $textFormat); // A,B
				} elseif ($filter_report == 'region_state') {
				$worksheet->write($i, $j++, $result['payment_zone']. ', ' . $result['payment_country'], $textFormat); // A,B
				} elseif ($filter_report == 'city') {
				$worksheet->write($i, $j++, $result['payment_city']. ', ' . $result['payment_country'], $textFormat); // A,B
				} elseif ($filter_report == 'payment_method') {
				$worksheet->write($i, $j++, preg_replace('~\(.*?\)~', '', $result['payment_method']), $textFormat); // A,B
				} elseif ($filter_report == 'shipping_method') {
				$worksheet->write($i, $j++, preg_replace('~\(.*?\)~', '', $result['shipping_method']), $textFormat); // A,B
				}				
				isset($_POST['so20']) ? $worksheet->write($i, $j++, $result['orders']) : ''; // C
				isset($_POST['so21']) ? $worksheet->write($i, $j++, $result['customers']) : ''; // D
				isset($_POST['so22']) ? $worksheet->write($i, $j++, $result['products']) : ''; // E
				isset($_POST['so23']) ? $worksheet->write($i, $j++, $result['sub_total'], $priceFormat) : ''; // F
				isset($_POST['so24']) ? $worksheet->write($i, $j++, $result['handling'] != NULL ? $result['handling'] : '0.00', $priceFormat) : ''; // G
				isset($_POST['so25']) ? $worksheet->write($i, $j++, $result['low_order_fee'] != NULL ? $result['low_order_fee'] : '0.00', $priceFormat) : ''; // H
				isset($_POST['so27']) ? $worksheet->write($i, $j++, $result['shipping'] != NULL ? $result['shipping'] : '0.00', $priceFormat) : ''; // I
				isset($_POST['so26']) ? $worksheet->write($i, $j++, $result['reward'] != NULL ? $result['reward'] : '0.00', $priceFormat) : ''; // J
				isset($_POST['so28']) ? $worksheet->write($i, $j++, $result['coupon'] != NULL ? $result['coupon'] : '0.00', $priceFormat) : ''; // K
				isset($_POST['so29']) ? $worksheet->write($i, $j++, $result['tax'] != NULL ? $result['tax'] : '0.00', $priceFormat) : ''; // L
				isset($_POST['so30']) ? $worksheet->write($i, $j++, $result['credit'] != NULL ? $result['credit'] : '0.00', $priceFormat) : ''; // M
				isset($_POST['so31']) ? $worksheet->write($i, $j++, $result['voucher'] != NULL ? $result['voucher'] : '0.00', $priceFormat) : ''; // N
				isset($_POST['so32']) ? $worksheet->write($i, $j++, -$result['commission'], $priceFormat) : ''; // O
				isset($_POST['so33']) ? $worksheet->write($i, $j++, $result['total'], $priceFormat) : ''; // P

				$i += 1;
				$j = 0;
			}
		
		$worksheet->freezePanes(array(1, 0, 1, 0));
		
		// Let's send the file		
		$workbook->close();
		
		// Clear the spreadsheet caches
		$this->clearSpreadsheetCache();
		exit;
?>
<?php
ini_set("memory_limit","256M");

	$this->data['decimal_point'] = $this->language->get('decimal_point');
	if ($this->data['decimal_point'] == ',') {
	$csv_separator = ";";
	} else {
	$csv_separator = ",";
	}
	$csv_enclosed = '"';
	$csv_row = "\n";	

	if ($filter_report == 'sales_summary') {
	if ($filter_group == 'year') {	
	$export_csv = $csv_enclosed . $this->language->get('column_year') . $csv_enclosed;
	} elseif ($filter_group == 'quarter') {
	$export_csv = $csv_enclosed . $this->language->get('column_year') . $csv_enclosed;				
	$export_csv .= $csv_separator . $csv_enclosed . $this->language->get('column_quarter') . $csv_enclosed;			
	} elseif ($filter_group == 'month') {
	$export_csv = $csv_enclosed . $this->language->get('column_year') . $csv_enclosed;			
	$export_csv .= $csv_separator . $csv_enclosed . $this->language->get('column_month') . $csv_enclosed;	
	} elseif ($filter_group == 'day') {
	$export_csv = $csv_enclosed . $this->language->get('column_date') . $csv_enclosed;
	} elseif ($filter_group == 'order') {
	$export_csv = $csv_enclosed . $this->language->get('column_order_order_id') . $csv_enclosed;				
	$export_csv .= $csv_separator . $csv_enclosed . $this->language->get('column_order_date_added') . $csv_enclosed;	
	} else {
	$export_csv = $csv_enclosed . $this->language->get('column_date_start') . $csv_enclosed;					
	$export_csv .= $csv_separator . $csv_enclosed . $this->language->get('column_date_end') . $csv_enclosed;	
	}
	} elseif ($filter_report == 'day_of_week') {
	$export_csv = $csv_enclosed . $this->language->get('column_day_of_week') . $csv_enclosed;
	} elseif ($filter_report == 'hour') {
	$export_csv = $csv_enclosed . $this->language->get('column_hour') . $csv_enclosed;
	} elseif ($filter_report == 'store') {
	$export_csv = $csv_enclosed . $this->language->get('column_store') . $csv_enclosed;
	} elseif ($filter_report == 'customer_group') {
	$export_csv = $csv_enclosed . $this->language->get('column_customer_group') . $csv_enclosed;
	} elseif ($filter_report == 'country') {
	$export_csv = $csv_enclosed . $this->language->get('column_country') . $csv_enclosed;
	} elseif ($filter_report == 'postcode') {
	$export_csv = $csv_enclosed . $this->language->get('column_postcode') . $csv_enclosed;
	} elseif ($filter_report == 'region_state') {
	$export_csv = $csv_enclosed . $this->language->get('column_region_state') . $csv_enclosed;
	} elseif ($filter_report == 'city') {
	$export_csv = $csv_enclosed . $this->language->get('column_city') . $csv_enclosed;
	} elseif ($filter_report == 'payment_method') {
	$export_csv = $csv_enclosed . $this->language->get('column_payment_method') . $csv_enclosed;
	} elseif ($filter_report == 'shipping_method') {
	$export_csv = $csv_enclosed . $this->language->get('column_shipping_method') . $csv_enclosed;	
	}		
	isset($_POST['so20']) ? $export_csv .= $csv_separator . $csv_enclosed . $this->language->get('column_orders') . $csv_enclosed : '';
	isset($_POST['so21']) ? $export_csv .= $csv_separator . $csv_enclosed . $this->language->get('column_customers') . $csv_enclosed : '';
	isset($_POST['so22']) ? $export_csv .= $csv_separator . $csv_enclosed . $this->language->get('column_products') . $csv_enclosed : '';
	isset($_POST['so23']) ? $export_csv .= $csv_separator . $csv_enclosed . $this->language->get('column_sub_total') . $csv_enclosed : '';
	isset($_POST['so24']) ? $export_csv .= $csv_separator . $csv_enclosed . $this->language->get('column_handling') . $csv_enclosed : '';
	isset($_POST['so25']) ? $export_csv .= $csv_separator . $csv_enclosed . $this->language->get('column_loworder') . $csv_enclosed : '';
	isset($_POST['so27']) ? $export_csv .= $csv_separator . $csv_enclosed . $this->language->get('column_shipping') . $csv_enclosed : '';	
	isset($_POST['so26']) ? $export_csv .= $csv_separator . $csv_enclosed . $this->language->get('column_reward') . $csv_enclosed : '';
	isset($_POST['so28']) ? $export_csv .= $csv_separator . $csv_enclosed . $this->language->get('column_coupon') . $csv_enclosed : '';
	isset($_POST['so29']) ? $export_csv .= $csv_separator . $csv_enclosed . $this->language->get('column_tax') . $csv_enclosed : '';
	isset($_POST['so30']) ? $export_csv .= $csv_separator . $csv_enclosed . $this->language->get('column_credit') . $csv_enclosed : '';
	isset($_POST['so31']) ? $export_csv .= $csv_separator . $csv_enclosed . $this->language->get('column_voucher') . $csv_enclosed : '';
	isset($_POST['so32']) ? $export_csv .= $csv_separator . $csv_enclosed . $this->language->get('column_commission') . $csv_enclosed : '';
	isset($_POST['so33']) ? $export_csv .= $csv_separator . $csv_enclosed . $this->language->get('column_total') . $csv_enclosed : '';
	$export_csv .= $csv_row;

	foreach ($results as $result) {
	if ($filter_report == 'sales_summary') {
	if ($filter_group == 'year') {					
	$export_csv .= $csv_enclosed . $result['year'] . $csv_enclosed;
	} elseif ($filter_group == 'quarter') {
	$export_csv .= $csv_enclosed . $result['year'] . $csv_enclosed;				
	$export_csv .= $csv_separator . $csv_enclosed . 'Q' . $result['quarter'] . $csv_enclosed;			
	} elseif ($filter_group == 'month') {
	$export_csv .= $csv_enclosed . $result['year'] . $csv_enclosed;			
	$export_csv .= $csv_separator . $csv_enclosed . $result['month'] . $csv_enclosed;	
	} elseif ($filter_group == 'day') {
	$export_csv .= $csv_enclosed . date($this->language->get('date_format_short'), strtotime($result['date_start'])) . $csv_enclosed;
	} elseif ($filter_group == 'order') {
	$export_csv .= $csv_enclosed . $result['order_id'] . $csv_enclosed;				
	$export_csv .= $csv_separator . $csv_enclosed . date($this->language->get('date_format_short'), strtotime($result['date_start'])) . $csv_enclosed;	
	} else {
	$export_csv .= $csv_enclosed . date($this->language->get('date_format_short'), strtotime($result['date_start'])) . $csv_enclosed;					
	$export_csv .= $csv_separator . $csv_enclosed . date($this->language->get('date_format_short'), strtotime($result['date_end'])) . $csv_enclosed;	
	}
	} elseif ($filter_report == 'day_of_week') {
	$export_csv .= $csv_enclosed . $result['day_of_week'] . $csv_enclosed;
	} elseif ($filter_report == 'hour') {
	$export_csv .= $csv_enclosed . number_format($result['hour'], 2, ':', '') . $csv_enclosed;
	} elseif ($filter_report == 'store') {
	$export_csv .= $csv_enclosed . html_entity_decode($result['store_name']) . $csv_enclosed;
	} elseif ($filter_report == 'customer_group') {
	$export_csv .= $csv_enclosed . html_entity_decode($result['customer_group']) . $csv_enclosed;
	} elseif ($filter_report == 'country') {
	$export_csv .= $csv_enclosed . $result['payment_country'] . $csv_enclosed;
	} elseif ($filter_report == 'postcode') {
	$export_csv .= $csv_enclosed . $result['payment_postcode'] . $csv_enclosed;
	} elseif ($filter_report == 'region_state') {
	$export_csv .= $csv_enclosed . $result['payment_zone']. ', ' . $result['payment_country'] . $csv_enclosed;
	} elseif ($filter_report == 'city') {
	$export_csv .= $csv_enclosed . $result['payment_city']. ', ' . $result['payment_country'] . $csv_enclosed;
	} elseif ($filter_report == 'payment_method') {
	$export_csv .= $csv_enclosed . preg_replace('~\(.*?\)~', '', $result['payment_method']) . $csv_enclosed;
	} elseif ($filter_report == 'shipping_method') {
	$export_csv .= $csv_enclosed . preg_replace('~\(.*?\)~', '', $result['shipping_method']) . $csv_enclosed;
	}	
	isset($_POST['so20']) ? $export_csv .= $csv_separator . $csv_enclosed . number_format($result['orders']) . $csv_enclosed : '';
	isset($_POST['so21']) ? $export_csv .= $csv_separator . $csv_enclosed . number_format($result['customers']) . $csv_enclosed : '';
	isset($_POST['so22']) ? $export_csv .= $csv_separator . $csv_enclosed . number_format($result['products']) . $csv_enclosed : '';
	isset($_POST['so23']) ? $export_csv .= $csv_separator . $csv_enclosed . number_format($result['sub_total'], 2) . $csv_enclosed : '';
	isset($_POST['so24']) ? $export_csv .= $csv_separator . $csv_enclosed . number_format($result['handling'], 2) . $csv_enclosed : '';
	isset($_POST['so25']) ? $export_csv .= $csv_separator . $csv_enclosed . number_format($result['low_order_fee'], 2) . $csv_enclosed : '';
	isset($_POST['so27']) ? $export_csv .= $csv_separator . $csv_enclosed . number_format($result['shipping'], 2) . $csv_enclosed : '';
	isset($_POST['so26']) ? $export_csv .= $csv_separator . $csv_enclosed . number_format($result['reward'], 2) . $csv_enclosed : '';
	isset($_POST['so28']) ? $export_csv .= $csv_separator . $csv_enclosed . number_format($result['coupon'], 2) . $csv_enclosed : '';
	isset($_POST['so29']) ? $export_csv .= $csv_separator . $csv_enclosed . number_format($result['tax'], 2) . $csv_enclosed : '';
	isset($_POST['so30']) ? $export_csv .= $csv_separator . $csv_enclosed . number_format($result['credit'], 2) . $csv_enclosed : '';
	isset($_POST['so31']) ? $export_csv .= $csv_separator . $csv_enclosed . number_format($result['voucher'], 2) . $csv_enclosed : '';
	isset($_POST['so32']) ? $export_csv .= $csv_separator . $csv_enclosed . number_format('-' . ($result['commission']), 2) . $csv_enclosed : '';
	isset($_POST['so33']) ? $export_csv .= $csv_separator . $csv_enclosed . number_format($result['total'], 2) . $csv_enclosed : '';	
	$export_csv .= $csv_row;
	}

$filename = "sale_order_report_".date("Y-m-d",time());
header('Pragma: public');
header('Expires: 0');
header('Content-Description: File Transfer');
header('Content-Type: text/csv; charset=utf-8');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');		
header('Content-Disposition: attachment; filename='.$filename.".csv");
print $export_csv;			
exit;
?>
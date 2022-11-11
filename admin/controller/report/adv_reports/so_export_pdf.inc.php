<?php
ini_set("memory_limit","256M");
	
	$export_pdf = "<html><head>";			
	$export_pdf .= "</head>";
	$export_pdf .= "<body>";
	$export_pdf .= "<style type='text/css'>
	.list_main {
		border-collapse: collapse;		
		width: 100%;
		border-top: 1px solid #DDDDDD;
		border-left: 1px solid #DDDDDD;	
		font-family: Helvetica;
		font-size: 11px;
	}
	.list_main td {
		border-right: 1px solid #DDDDDD;
		border-bottom: 1px solid #DDDDDD;	
	}
	.list_main thead td {
		background-color: #E5E5E5;
		padding: 3px;
		font-weight: bold;
	}	
	.list_main tbody a {
		text-decoration: none;
	}
	.list_main tbody td {
		vertical-align: middle;
		padding: 3px;
	}
	
	.total {
		background-color: #E7EFEF;
		color: #003A88;
		font-weight: bold;
	}
	</style>";
	$export_pdf .= "<table class='list_main'>";
	$export_pdf .="<thead>";		
	$export_pdf .= "<tr>";
	if ($filter_report == 'sales_summary') {
	if ($filter_group == 'year') {					
	$export_pdf .= "<td colspan='2' align='left' nowrap='nowrap'>".$this->language->get('column_year')."</td>";
	} elseif ($filter_group == 'quarter') {
	$export_pdf .= "<td align='left' nowrap='nowrap'>".$this->language->get('column_year')."</td>";
	$export_pdf .= "<td align='left' nowrap='nowrap'>".$this->language->get('column_quarter')."</td>";				
	} elseif ($filter_group == 'month') {
	$export_pdf .= "<td align='left' nowrap='nowrap'>".$this->language->get('column_year')."</td>";
	$export_pdf .= "<td align='left' nowrap='nowrap'>".$this->language->get('column_month')."</td>";
	} elseif ($filter_group == 'day') {
	$export_pdf .= "<td colspan='2' align='left' nowrap='nowrap'>".$this->language->get('column_date')."</td>";
	} elseif ($filter_group == 'order') {
	$export_pdf .= "<td align='left' nowrap='nowrap'>".$this->language->get('column_order_order_id')."</td>";
	$export_pdf .= "<td align='left' nowrap='nowrap'>".$this->language->get('column_order_date_added')."</td>";	
	} else {
	$export_pdf .= "<td align='left' nowrap='nowrap'>".$this->language->get('column_date_start')."</td>";
	$export_pdf .= "<td align='left' nowrap='nowrap'>".$this->language->get('column_date_end')."</td>";	
	}
	} elseif ($filter_report == 'day_of_week') {
	$export_pdf .= "<td colspan='2' align='left' nowrap='nowrap'>".$this->language->get('column_day_of_week')."</td>";
	} elseif ($filter_report == 'hour') {
	$export_pdf .= "<td colspan='2' align='left' nowrap='nowrap'>".$this->language->get('column_hour')."</td>";
	} elseif ($filter_report == 'store') {
	$export_pdf .= "<td colspan='2' align='left' nowrap='nowrap'>".$this->language->get('column_store')."</td>";
	} elseif ($filter_report == 'customer_group') {
	$export_pdf .= "<td colspan='2' align='left' nowrap='nowrap'>".$this->language->get('column_customer_group')."</td>";
	} elseif ($filter_report == 'country') {
	$export_pdf .= "<td colspan='2' align='left' nowrap='nowrap'>".$this->language->get('column_country')."</td>";
	} elseif ($filter_report == 'postcode') {
	$export_pdf .= "<td colspan='2' align='left' nowrap='nowrap'>".$this->language->get('column_postcode')."</td>";
	} elseif ($filter_report == 'region_state') {
	$export_pdf .= "<td colspan='2' align='left' nowrap='nowrap'>".$this->language->get('column_region_state')."</td>";
	} elseif ($filter_report == 'city') {
	$export_pdf .= "<td colspan='2' align='left' nowrap='nowrap'>".$this->language->get('column_city')."</td>";
	} elseif ($filter_report == 'payment_method') {
	$export_pdf .= "<td colspan='2' align='left' nowrap='nowrap'>".$this->language->get('column_payment_method')."</td>";
	} elseif ($filter_report == 'shipping_method') {
	$export_pdf .= "<td colspan='2' align='left' nowrap='nowrap'>".$this->language->get('column_shipping_method')."</td>";	
	}
	isset($_POST['so20']) ? $export_pdf .= "<td align='right'>".$this->language->get('column_orders')."</td>" : '';
	isset($_POST['so21']) ? $export_pdf .= "<td align='right'>".$this->language->get('column_customers')."</td>" : '';
	isset($_POST['so22']) ? $export_pdf .= "<td align='right'>".$this->language->get('column_products')."</td>" : '';
	isset($_POST['so23']) ? $export_pdf .= "<td align='right'>".$this->language->get('column_sub_total')."</td>" : '';
	isset($_POST['so24']) ? $export_pdf .= "<td align='right'>".$this->language->get('column_handling')."</td>" : '';
	isset($_POST['so25']) ? $export_pdf .= "<td align='right'>".$this->language->get('column_loworder')."</td>" : '';
	isset($_POST['so27']) ? $export_pdf .= "<td align='right'>".$this->language->get('column_shipping')."</td>" : '';	
	isset($_POST['so26']) ? $export_pdf .= "<td align='right'>".$this->language->get('column_reward')."</td>" : '';
	isset($_POST['so28']) ? $export_pdf .= "<td align='right'>".$this->language->get('column_coupon')."</td>" : '';
	isset($_POST['so29']) ? $export_pdf .= "<td align='right'>".$this->language->get('column_tax')."</td>" : '';
	isset($_POST['so30']) ? $export_pdf .= "<td align='right'>".$this->language->get('column_credit')."</td>" : '';
	isset($_POST['so31']) ? $export_pdf .= "<td align='right'>".$this->language->get('column_voucher')."</td>" : '';
	isset($_POST['so32']) ? $export_pdf .= "<td align='right'>".$this->language->get('column_commission')."</td>" : '';
	isset($_POST['so33']) ? $export_pdf .= "<td align='right'>".$this->language->get('column_total')."</td>" : '';
	$export_pdf .= "</tr>";
	$export_pdf .= "</thead><tbody>";
	foreach ($results as $result) {		
	$export_pdf .= "<tr>";
	if ($filter_report == 'sales_summary') {
	if ($filter_group == 'year') {					
	$export_pdf .= "<td colspan='2' align='left' nowrap='nowrap' style='background-color:#F0F0F0;'>".$result['year']."</td>";
	} elseif ($filter_group == 'quarter') {
	$export_pdf .= "<td align='left' nowrap='nowrap' style='background-color:#F0F0F0;'>".$result['year']."</td>";	
	$export_pdf .= "<td align='left' nowrap='nowrap' style='background-color:#F0F0F0;'>".'Q' . $result['quarter']."</td>";						
	} elseif ($filter_group == 'month') {
	$export_pdf .= "<td align='left' nowrap='nowrap' style='background-color:#F0F0F0;'>".$result['year']."</td>";	
	$export_pdf .= "<td align='left' nowrap='nowrap' style='background-color:#F0F0F0;'>".$result['month']."</td>";	
	} elseif ($filter_group == 'day') {
	$export_pdf .= "<td colspan='2' align='left' nowrap='nowrap' style='background-color:#F0F0F0;'>".date($this->language->get('date_format_short'), strtotime($result['date_start']))."</td>";	
	} elseif ($filter_group == 'order') {
	$export_pdf .= "<td align='left' nowrap='nowrap' style='background-color:#F0F0F0;'>".$result['order_id']."</td>";	
	$export_pdf .= "<td align='left' nowrap='nowrap' style='background-color:#F0F0F0;'>".date($this->language->get('date_format_short'), strtotime($result['date_start']))."</td>";	
	} else {
	$export_pdf .= "<td align='left' nowrap='nowrap' style='background-color:#F0F0F0;'>".date($this->language->get('date_format_short'), strtotime($result['date_start']))."</td>";
	$export_pdf .= "<td align='left' nowrap='nowrap' style='background-color:#F0F0F0;'>".date($this->language->get('date_format_short'), strtotime($result['date_end']))."</td>";
	}
	} elseif ($filter_report == 'day_of_week') {
	$export_pdf .= "<td colspan='2' align='left' nowrap='nowrap' style='background-color:#F0F0F0;'>".$result['day_of_week']."</td>";
	} elseif ($filter_report == 'hour') {
	$export_pdf .= "<td colspan='2' align='left' nowrap='nowrap' style='background-color:#F0F0F0;'>".number_format($result['hour'], 2, ':', '')."</td>";
	} elseif ($filter_report == 'store') {
	$export_pdf .= "<td colspan='2' align='left' nowrap='nowrap' style='background-color:#F0F0F0;'>".html_entity_decode($result['store_name'])."</td>";
	} elseif ($filter_report == 'customer_group') {
	$export_pdf .= "<td colspan='2' align='left' nowrap='nowrap' style='background-color:#F0F0F0;'>".html_entity_decode($result['customer_group'])."</td>";
	} elseif ($filter_report == 'country') {
	$export_pdf .= "<td colspan='2' align='left' nowrap='nowrap' style='background-color:#F0F0F0;'>".$result['payment_country']."</td>";
	} elseif ($filter_report == 'postcode') {
	$export_pdf .= "<td colspan='2' align='left' nowrap='nowrap' style='background-color:#F0F0F0;'>".$result['payment_postcode']."</td>";
	} elseif ($filter_report == 'region_state') {
	$export_pdf .= "<td colspan='2' align='left' nowrap='nowrap' style='background-color:#F0F0F0;'>".$result['payment_zone']. ', ' . $result['payment_country']."</td>";
	} elseif ($filter_report == 'city') {
	$export_pdf .= "<td colspan='2' align='left' nowrap='nowrap' style='background-color:#F0F0F0;'>".$result['payment_city']. ', ' . $result['payment_country']."</td>";
	} elseif ($filter_report == 'payment_method') {
	$export_pdf .= "<td colspan='2' align='left' nowrap='nowrap' style='background-color:#F0F0F0;'>".preg_replace('~\(.*?\)~', '', $result['payment_method'])."</td>";
	} elseif ($filter_report == 'shipping_method') {
	$export_pdf .= "<td colspan='2' align='left' nowrap='nowrap' style='background-color:#F0F0F0;'>".preg_replace('~\(.*?\)~', '', $result['shipping_method'])."</td>";
	}	
	isset($_POST['so20']) ? $export_pdf .= "<td align='right' nowrap='nowrap'>".$result['orders']."</td>" : '';
	isset($_POST['so21']) ? $export_pdf .= "<td align='right' nowrap='nowrap'>".$result['customers']."</td>" : '';
	isset($_POST['so22']) ? $export_pdf .= "<td align='right' nowrap='nowrap'>".$result['products']."</td>" : '';
	isset($_POST['so23']) ? $export_pdf .= "<td align='right' nowrap='nowrap'>".$this->currency->format($result['sub_total'], $this->config->get('config_currency'))."</td>" : '';
	isset($_POST['so24']) ? $export_pdf .= "<td align='right' nowrap='nowrap'>".$this->currency->format($result['handling'], $this->config->get('config_currency'))."</td>" : '';
	isset($_POST['so25']) ? $export_pdf .= "<td align='right' nowrap='nowrap'>".$this->currency->format($result['low_order_fee'], $this->config->get('config_currency'))."</td>" : '';
	isset($_POST['so27']) ? $export_pdf .= "<td align='right' nowrap='nowrap'>".$this->currency->format($result['shipping'], $this->config->get('config_currency'))."</td>" : '';	
	isset($_POST['so26']) ? $export_pdf .= "<td align='right' nowrap='nowrap'>".$this->currency->format($result['reward'], $this->config->get('config_currency'))."</td>" : '';
	isset($_POST['so28']) ? $export_pdf .= "<td align='right' nowrap='nowrap'>".$this->currency->format($result['coupon'], $this->config->get('config_currency'))."</td>" : '';
	isset($_POST['so29']) ? $export_pdf .= "<td align='right' nowrap='nowrap'>".$this->currency->format($result['tax'], $this->config->get('config_currency'))."</td>" : '';
	isset($_POST['so30']) ? $export_pdf .= "<td align='right' nowrap='nowrap'>".$this->currency->format($result['credit'], $this->config->get('config_currency'))."</td>" : '';
	isset($_POST['so31']) ? $export_pdf .= "<td align='right' nowrap='nowrap'>".$this->currency->format($result['voucher'], $this->config->get('config_currency'))."</td>" : '';
	isset($_POST['so32']) ? $export_pdf .= "<td align='right' nowrap='nowrap'>".$this->currency->format('-' . ($result['commission']), $this->config->get('config_currency'))."</td>" : '';	
	isset($_POST['so33']) ? $export_pdf .= "<td align='right' nowrap='nowrap'>".$this->currency->format($result['total'], $this->config->get('config_currency'))."</td>" : '';
	}	
	$export_pdf .="<tr>";
	$export_pdf .= "<td colspan='2' align='right' style='background-color:#E7EFEF; font-weight:bold;'>".$this->language->get('text_filter_total')."</td>";
	isset($_POST['so20']) ? $export_pdf .= "<td align='right' nowrap='nowrap' class='total'>".$result['orders_total']."</td>" : '';
	isset($_POST['so21']) ? $export_pdf .= "<td align='right' nowrap='nowrap' class='total'>".$result['customers_total']."</td>" : '';
	isset($_POST['so22']) ? $export_pdf .= "<td align='right' nowrap='nowrap' class='total'>".$result['products_total']."</td>" : '';
	isset($_POST['so23']) ? $export_pdf .= "<td align='right' nowrap='nowrap' class='total'>".$this->currency->format($result['sub_total_total'], $this->config->get('config_currency'))."</td>" : '';
	isset($_POST['so24']) ? $export_pdf .= "<td align='right' nowrap='nowrap' class='total'>".$this->currency->format($result['handling_total'], $this->config->get('config_currency'))."</td>" : '';
	isset($_POST['so25']) ? $export_pdf .= "<td align='right' nowrap='nowrap' class='total'>".$this->currency->format($result['low_order_fee_total'], $this->config->get('config_currency'))."</td>" : '';
	isset($_POST['so27']) ? $export_pdf .= "<td align='right' nowrap='nowrap' class='total'>".$this->currency->format($result['shipping_total'], $this->config->get('config_currency'))."</td>" : '';
	isset($_POST['so26']) ? $export_pdf .= "<td align='right' nowrap='nowrap' class='total'>".$this->currency->format($result['reward_total'], $this->config->get('config_currency'))."</td>" : '';
	isset($_POST['so28']) ? $export_pdf .= "<td align='right' nowrap='nowrap' class='total'>".$this->currency->format($result['coupon_total'], $this->config->get('config_currency'))."</td>" : '';
	isset($_POST['so29']) ? $export_pdf .= "<td align='right' nowrap='nowrap' class='total'>".$this->currency->format($result['tax_total'], $this->config->get('config_currency'))."</td>" : '';
	isset($_POST['so30']) ? $export_pdf .= "<td align='right' nowrap='nowrap' class='total'>".$this->currency->format($result['credit_total'], $this->config->get('config_currency'))."</td>" : '';
	isset($_POST['so31']) ? $export_pdf .= "<td align='right' nowrap='nowrap' class='total'>".$this->currency->format($result['voucher_total'], $this->config->get('config_currency'))."</td>" : '';
	isset($_POST['so32']) ? $export_pdf .= "<td align='right' nowrap='nowrap' class='total'>".$this->currency->format('-' . ($result['commission_total']), $this->config->get('config_currency'))."</td>" : '';
	isset($_POST['so33']) ? $export_pdf .= "<td align='right' nowrap='nowrap' class='total'>".$this->currency->format($result['total_total'], $this->config->get('config_currency'))."</td>" : '';
	$export_pdf .="</tr></tbody></table>";	
	$export_pdf .="</body></html>";

ini_set('mbstring.substitute_character', "none"); 
$dompdf_pdf = mb_convert_encoding($export_pdf, 'ISO-8859-1', 'UTF-8'); 
$dompdf = new DOMPDF();
$dompdf->load_html($dompdf_pdf);
$dompdf->set_paper("a3", "landscape");
$dompdf->render();
$dompdf->stream("sale_order_report_".date("Y-m-d",time()).".pdf");
?>
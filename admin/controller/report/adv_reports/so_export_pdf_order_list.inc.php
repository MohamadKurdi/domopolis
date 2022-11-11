<?php
ini_set("memory_limit","256M");

	$export_pdf_order_list = "<html><head>";			
	$export_pdf_order_list .= "</head>";
	$export_pdf_order_list .= "<body>";
	$export_pdf_order_list .= "<style type='text/css'>
	.list_main {
		width: 100%;
		font-family: Helvetica;
		margin-bottom: 5px;
	}
	.list_main thead td {
		border: 1px solid #DDDDDD;			
		background-color: #E5E5E5;
		padding: 0px 3px;
		font-size: 11px;
		font-weight: bold;
	}	
	.list_main tbody a {
		text-decoration: none;
	}
	.list_main tbody td {
		border: 1px solid #DDDDDD;
		padding: 3px;
		font-size: 11px;	
	}

	.list_detail {
		width: 100%;
		font-family: Helvetica;			
	}
	.list_detail thead td {
		border: 1px solid #DDDDDD;		
		background-color: #F0F0F0;
		padding: 0px 3px;
		font-size: 9px;
		font-weight: bold;
	}	
	.list_detail tbody td {
		border: 1px solid #DDDDDD;
		padding: 0px 3px;
		font-size: 9px;	
	}
	
	.total {
		background-color: #E7EFEF;
		color: #003A88;
		font-weight: bold;
	}
	</style>";	
	foreach ($results as $result) {
	$export_pdf_order_list .= "<div style='border:1px solid #999; padding: 3px; margin-bottom:10px; width:100%;'>";
	$export_pdf_order_list .= "<table cellspacing='0' cellpadding='0' class='list_main'>";	
	$export_pdf_order_list .="<thead><tr>";	
	if ($filter_report == 'sales_summary') {
	if ($filter_group == 'year') {					
	$export_pdf_order_list .= "<td colspan='2' align='left' nowrap='nowrap'>".$this->language->get('column_year')."</td>";
	} elseif ($filter_group == 'quarter') {
	$export_pdf_order_list .= "<td align='left' nowrap='nowrap'>".$this->language->get('column_year')."</td>";
	$export_pdf_order_list .= "<td align='left' nowrap='nowrap'>".$this->language->get('column_quarter')."</td>";				
	} elseif ($filter_group == 'month') {
	$export_pdf_order_list .= "<td align='left' nowrap='nowrap'>".$this->language->get('column_year')."</td>";
	$export_pdf_order_list .= "<td align='left' nowrap='nowrap'>".$this->language->get('column_month')."</td>";
	} elseif ($filter_group == 'day') {
	$export_pdf_order_list .= "<td colspan='2' align='left' nowrap='nowrap'>".$this->language->get('column_date')."</td>";
	} elseif ($filter_group == 'order') {
	$export_pdf_order_list .= "<td align='left' nowrap='nowrap'>".$this->language->get('column_order_order_id')."</td>";
	$export_pdf_order_list .= "<td align='left' nowrap='nowrap'>".$this->language->get('column_order_date_added')."</td>";	
	} else {
	$export_pdf_order_list .= "<td align='left' nowrap='nowrap'>".$this->language->get('column_date_start')."</td>";
	$export_pdf_order_list .= "<td align='left' nowrap='nowrap'>".$this->language->get('column_date_end')."</td>";	
	}
	} elseif ($filter_report == 'day_of_week') {
	$export_pdf_order_list .= "<td colspan='2' align='left' nowrap='nowrap'>".$this->language->get('column_day_of_week')."</td>";
	} elseif ($filter_report == 'hour') {
	$export_pdf_order_list .= "<td colspan='2' align='left' nowrap='nowrap'>".$this->language->get('column_hour')."</td>";
	} elseif ($filter_report == 'store') {
	$export_pdf_order_list .= "<td colspan='2' align='left' nowrap='nowrap'>".$this->language->get('column_store')."</td>";
	} elseif ($filter_report == 'customer_group') {
	$export_pdf_order_list .= "<td colspan='2' align='left' nowrap='nowrap'>".$this->language->get('column_customer_group')."</td>";
	} elseif ($filter_report == 'country') {
	$export_pdf_order_list .= "<td colspan='2' align='left' nowrap='nowrap'>".$this->language->get('column_country')."</td>";
	} elseif ($filter_report == 'postcode') {
	$export_pdf_order_list .= "<td colspan='2' align='left' nowrap='nowrap'>".$this->language->get('column_postcode')."</td>";
	} elseif ($filter_report == 'region_state') {
	$export_pdf_order_list .= "<td colspan='2' align='left' nowrap='nowrap'>".$this->language->get('column_region_state')."</td>";
	} elseif ($filter_report == 'city') {
	$export_pdf_order_list .= "<td colspan='2' align='left' nowrap='nowrap'>".$this->language->get('column_city')."</td>";
	} elseif ($filter_report == 'payment_method') {
	$export_pdf_order_list .= "<td colspan='2' align='left' nowrap='nowrap'>".$this->language->get('column_payment_method')."</td>";
	} elseif ($filter_report == 'shipping_method') {
	$export_pdf_order_list .= "<td colspan='2' align='left' nowrap='nowrap'>".$this->language->get('column_shipping_method')."</td>";	
	}		
	isset($_POST['so20']) ? $export_pdf_order_list .= "<td align='right'>".$this->language->get('column_orders')."</td>" : '';
	isset($_POST['so21']) ? $export_pdf_order_list .= "<td align='right'>".$this->language->get('column_customers')."</td>" : '';
	isset($_POST['so22']) ? $export_pdf_order_list .= "<td align='right'>".$this->language->get('column_products')."</td>" : '';
	isset($_POST['so23']) ? $export_pdf_order_list .= "<td align='right'>".$this->language->get('column_sub_total')."</td>" : '';
	isset($_POST['so24']) ? $export_pdf_order_list .= "<td align='right'>".$this->language->get('column_handling')."</td>" : '';
	isset($_POST['so25']) ? $export_pdf_order_list .= "<td align='right'>".$this->language->get('column_loworder')."</td>" : '';
	isset($_POST['so27']) ? $export_pdf_order_list .= "<td align='right'>".$this->language->get('column_shipping')."</td>" : '';	
	isset($_POST['so26']) ? $export_pdf_order_list .= "<td align='right'>".$this->language->get('column_reward')."</td>" : '';
	isset($_POST['so28']) ? $export_pdf_order_list .= "<td align='right'>".$this->language->get('column_coupon')."</td>" : '';
	isset($_POST['so29']) ? $export_pdf_order_list .= "<td align='right'>".$this->language->get('column_tax')."</td>" : '';
	isset($_POST['so30']) ? $export_pdf_order_list .= "<td align='right'>".$this->language->get('column_credit')."</td>" : '';
	isset($_POST['so31']) ? $export_pdf_order_list .= "<td align='right'>".$this->language->get('column_voucher')."</td>" : '';
	isset($_POST['so32']) ? $export_pdf_order_list .= "<td align='right'>".$this->language->get('column_commission')."</td>" : '';	
	isset($_POST['so33']) ? $export_pdf_order_list .= "<td align='right'>".$this->language->get('column_total')."</td>" : '';
	$export_pdf_order_list .= "</tr></thead>";
	$export_pdf_order_list .= "<tbody><tr>";
	if ($filter_report == 'sales_summary') {
	if ($filter_group == 'year') {					
	$export_pdf_order_list .= "<td colspan='2' align='left' nowrap='nowrap'>".$result['year']."</td>";
	} elseif ($filter_group == 'quarter') {
	$export_pdf_order_list .= "<td align='left' nowrap='nowrap'>".$result['year']."</td>";	
	$export_pdf_order_list .= "<td align='left' nowrap='nowrap'>".'Q' . $result['quarter']."</td>";						
	} elseif ($filter_group == 'month') {
	$export_pdf_order_list .= "<td align='left' nowrap='nowrap'>".$result['year']."</td>";	
	$export_pdf_order_list .= "<td align='left' nowrap='nowrap'>".$result['month']."</td>";	
	} elseif ($filter_group == 'day') {
	$export_pdf_order_list .= "<td colspan='2' align='left' nowrap='nowrap'>".date($this->language->get('date_format_short'), strtotime($result['date_start']))."</td>";	
	} elseif ($filter_group == 'order') {
	$export_pdf_order_list .= "<td align='left' nowrap='nowrap'>".$result['order_id']."</td>";	
	$export_pdf_order_list .= "<td align='left' nowrap='nowrap'>".date($this->language->get('date_format_short'), strtotime($result['date_start']))."</td>";	
	} else {
	$export_pdf_order_list .= "<td align='left' nowrap='nowrap'>".date($this->language->get('date_format_short'), strtotime($result['date_start']))."</td>";
	$export_pdf_order_list .= "<td align='left' nowrap='nowrap'>".date($this->language->get('date_format_short'), strtotime($result['date_end']))."</td>";
	}
	} elseif ($filter_report == 'day_of_week') {
	$export_pdf_order_list .= "<td colspan='2' align='left' nowrap='nowrap' style='background-color:#F0F0F0;'>".$result['day_of_week']."</td>";
	} elseif ($filter_report == 'hour') {
	$export_pdf_order_list .= "<td colspan='2' align='left' nowrap='nowrap' style='background-color:#F0F0F0;'>".number_format($result['hour'], 2, ':', '')."</td>";
	} elseif ($filter_report == 'store') {
	$export_pdf_order_list .= "<td colspan='2' align='left' nowrap='nowrap' style='background-color:#F0F0F0;'>".html_entity_decode($result['store_name'])."</td>";
	} elseif ($filter_report == 'customer_group') {
	$export_pdf_order_list .= "<td colspan='2' align='left' nowrap='nowrap' style='background-color:#F0F0F0;'>".html_entity_decode($result['customer_group'])."</td>";
	} elseif ($filter_report == 'country') {
	$export_pdf_order_list .= "<td colspan='2' align='left' nowrap='nowrap' style='background-color:#F0F0F0;'>".$result['payment_country']."</td>";
	} elseif ($filter_report == 'postcode') {
	$export_pdf_order_list .= "<td colspan='2' align='left' nowrap='nowrap' style='background-color:#F0F0F0;'>".$result['payment_postcode']."</td>";
	} elseif ($filter_report == 'region_state') {
	$export_pdf_order_list .= "<td colspan='2' align='left' nowrap='nowrap' style='background-color:#F0F0F0;'>".$result['payment_zone']. ', ' . $result['payment_country']."</td>";
	} elseif ($filter_report == 'city') {
	$export_pdf_order_list .= "<td colspan='2' align='left' nowrap='nowrap' style='background-color:#F0F0F0;'>".$result['payment_city']. ', ' . $result['payment_country']."</td>";
	} elseif ($filter_report == 'payment_method') {
	$export_pdf_order_list .= "<td colspan='2' align='left' nowrap='nowrap' style='background-color:#F0F0F0;'>".preg_replace('~\(.*?\)~', '', $result['payment_method'])."</td>";
	} elseif ($filter_report == 'shipping_method') {
	$export_pdf_order_list .= "<td colspan='2' align='left' nowrap='nowrap' style='background-color:#F0F0F0;'>".preg_replace('~\(.*?\)~', '', $result['shipping_method'])."</td>";
	}	
	isset($_POST['so20']) ? $export_pdf_order_list .= "<td align='right' nowrap='nowrap'>".$result['orders']."</td>" : '';
	isset($_POST['so21']) ? $export_pdf_order_list .= "<td align='right' nowrap='nowrap'>".$result['customers']."</td>" : '';
	isset($_POST['so22']) ? $export_pdf_order_list .= "<td align='right' nowrap='nowrap'>".$result['products']."</td>" : '';
	isset($_POST['so23']) ? $export_pdf_order_list .= "<td align='right' nowrap='nowrap'>".$this->currency->format($result['sub_total'], $this->config->get('config_currency'))."</td>" : '';
	isset($_POST['so24']) ? $export_pdf_order_list .= "<td align='right' nowrap='nowrap'>".$this->currency->format($result['handling'], $this->config->get('config_currency'))."</td>" : '';
	isset($_POST['so25']) ? $export_pdf_order_list .= "<td align='right' nowrap='nowrap'>".$this->currency->format($result['low_order_fee'], $this->config->get('config_currency'))."</td>" : '';
	isset($_POST['so27']) ? $export_pdf_order_list .= "<td align='right' nowrap='nowrap'>".$this->currency->format($result['shipping'], $this->config->get('config_currency'))."</td>" : '';	
	isset($_POST['so26']) ? $export_pdf_order_list .= "<td align='right' nowrap='nowrap'>".$this->currency->format($result['reward'], $this->config->get('config_currency'))."</td>" : '';
	isset($_POST['so28']) ? $export_pdf_order_list .= "<td align='right' nowrap='nowrap'>".$this->currency->format($result['coupon'], $this->config->get('config_currency'))."</td>" : '';
	isset($_POST['so29']) ? $export_pdf_order_list .= "<td align='right' nowrap='nowrap'>".$this->currency->format($result['tax'], $this->config->get('config_currency'))."</td>" : '';
	isset($_POST['so30']) ? $export_pdf_order_list .= "<td align='right' nowrap='nowrap'>".$this->currency->format($result['credit'], $this->config->get('config_currency'))."</td>" : '';
	isset($_POST['so31']) ? $export_pdf_order_list .= "<td align='right' nowrap='nowrap'>".$this->currency->format($result['voucher'], $this->config->get('config_currency'))."</td>" : '';
	isset($_POST['so32']) ? $export_pdf_order_list .= "<td align='right' nowrap='nowrap'>".$this->currency->format('-' . ($result['commission']), $this->config->get('config_currency'))."</td>" : '';
	isset($_POST['so33']) ? $export_pdf_order_list .= "<td align='right' nowrap='nowrap'>".$this->currency->format($result['total'], $this->config->get('config_currency'))."</td>" : '';							
	$export_pdf_order_list .= "</tr></tbody></table>";
	$export_pdf_order_list .="<table cellspacing='0' cellpadding='0' cellspacing='0' cellpadding='0' class='list_detail'>";
	$export_pdf_order_list .="<thead><tr>";
		isset($_POST['so40']) ? $export_pdf_order_list .= "<td align='left'>".$this->language->get('column_order_order_id')."</td>" : '';
		isset($_POST['so41']) ? $export_pdf_order_list .= "<td align='left'>".$this->language->get('column_order_date_added')."</td>" : '';
		isset($_POST['so42']) ? $export_pdf_order_list .= "<td align='left'>".$this->language->get('column_order_inv_no')."</td>" : '';
		isset($_POST['so43']) ? $export_pdf_order_list .= "<td align='left'>".$this->language->get('column_order_customer')."</td>" : '';
		isset($_POST['so44']) ? $export_pdf_order_list .= "<td align='left'>".$this->language->get('column_order_email')."</td>" : '';
		isset($_POST['so45']) ? $export_pdf_order_list .= "<td align='left'>".$this->language->get('column_order_customer_group')."</td>" : '';
		isset($_POST['so46']) ? $export_pdf_order_list .= "<td align='left'>".$this->language->get('column_order_shipping_method')."</td>" : '';
		isset($_POST['so47']) ? $export_pdf_order_list .= "<td align='left'>".$this->language->get('column_order_payment_method')."</td>" : '';
		isset($_POST['so48']) ? $export_pdf_order_list .= "<td align='left'>".$this->language->get('column_order_status')."</td>" : '';
		isset($_POST['so49']) ? $export_pdf_order_list .= "<td align='left'>".$this->language->get('column_order_store')."</td>" : '';
		isset($_POST['so50']) ? $export_pdf_order_list .= "<td align='right'>".$this->language->get('column_order_currency')."</td>" : '';
		isset($_POST['so51']) ? $export_pdf_order_list .= "<td align='right'>".$this->language->get('column_order_quantity')."</td>" : '';
		isset($_POST['so52']) ? $export_pdf_order_list .= "<td align='right'>".$this->language->get('column_order_sub_total')."</td>" : '';
		isset($_POST['so54']) ? $export_pdf_order_list .= "<td align='right'>".$this->language->get('column_order_shipping')."</td>" : '';
		isset($_POST['so55']) ? $export_pdf_order_list .= "<td align='right'>".$this->language->get('column_order_tax')."</td>" : '';
		isset($_POST['so56']) ? $export_pdf_order_list .= "<td align='right'>".$this->language->get('column_order_value')."</td>" : '';
		$export_pdf_order_list .="</tr></thead>";
		$export_pdf_order_list .="<tbody><tr>";
		isset($_POST['so40']) ? $export_pdf_order_list .= "<td align='left' nowrap='nowrap'>".$result['order_ord_idc']."</td>" : '';
		isset($_POST['so41']) ? $export_pdf_order_list .= "<td align='left' nowrap='nowrap'>".$result['order_order_date']."</td>" : '';
		isset($_POST['so42']) ? $export_pdf_order_list .= "<td align='left' nowrap='nowrap'>".$result['order_inv_no']."</td>" : '';
		isset($_POST['so43']) ? $export_pdf_order_list .= "<td align='left' nowrap='nowrap'>".$result['order_name']."</td>" : '';
		isset($_POST['so44']) ? $export_pdf_order_list .= "<td align='left' nowrap='nowrap'>".$result['order_email']."</td>" : '';
		isset($_POST['so45']) ? $export_pdf_order_list .= "<td align='left' nowrap='nowrap'>".$result['order_group']."</td>" : '';
		isset($_POST['so46']) ? $export_pdf_order_list .= "<td align='left' nowrap='nowrap'>".strip_tags($result['order_shipping_method'], '<br>')."</td>" : '';
		isset($_POST['so47']) ? $export_pdf_order_list .= "<td align='left' nowrap='nowrap'>".strip_tags($result['order_payment_method'], '<br>')."</td>" : '';
		isset($_POST['so48']) ? $export_pdf_order_list .= "<td align='left' nowrap='nowrap'>".$result['order_status']."</td>" : '';
		isset($_POST['so49']) ? $export_pdf_order_list .= "<td align='left' nowrap='nowrap'>".$result['order_store']."</td>" : '';
		isset($_POST['so50']) ? $export_pdf_order_list .= "<td align='right' nowrap='nowrap'>".$result['order_currency']."</td>" : '';
		isset($_POST['so51']) ? $export_pdf_order_list .= "<td align='right' nowrap='nowrap'>".$result['order_products']."</td>" : '';
		isset($_POST['so52']) ? $export_pdf_order_list .= "<td align='right' nowrap='nowrap'>".$result['order_sub_total']."</td>" : '';
		isset($_POST['so54']) ? $export_pdf_order_list .= "<td align='right' nowrap='nowrap'>".$result['order_shipping']."</td>" : '';
		isset($_POST['so55']) ? $export_pdf_order_list .= "<td align='right' nowrap='nowrap'>".$result['order_tax']."</td>" : '';
		isset($_POST['so56']) ? $export_pdf_order_list .= "<td align='right' nowrap='nowrap'>".$result['order_value']."</td>" : '';
		$export_pdf_order_list .= "</tr></tbody></table>";
		$export_pdf_order_list .="</div>";			
	}	
	$export_pdf_order_list .="<table cellspacing='0' cellpadding='0' class='list_main'>";
	$export_pdf_order_list .="<thead><tr>";
	$export_pdf_order_list .= "<td colspan='2'></td>";	
	isset($_POST['so20']) ? $export_pdf_order_list .= "<td align='right'>".$this->language->get('column_orders')."</td>" : '';
	isset($_POST['so21']) ? $export_pdf_order_list .= "<td align='right'>".$this->language->get('column_customers')."</td>" : '';
	isset($_POST['so22']) ? $export_pdf_order_list .= "<td align='right'>".$this->language->get('column_products')."</td>" : '';
	isset($_POST['so23']) ? $export_pdf_order_list .= "<td align='right'>".$this->language->get('column_sub_total')."</td>" : '';
	isset($_POST['so24']) ? $export_pdf_order_list .= "<td align='right'>".$this->language->get('column_handling')."</td>" : '';
	isset($_POST['so25']) ? $export_pdf_order_list .= "<td align='right'>".$this->language->get('column_loworder')."</td>" : '';
	isset($_POST['so27']) ? $export_pdf_order_list .= "<td align='right'>".$this->language->get('column_shipping')."</td>" : '';	
	isset($_POST['so26']) ? $export_pdf_order_list .= "<td align='right'>".$this->language->get('column_reward')."</td>" : '';
	isset($_POST['so28']) ? $export_pdf_order_list .= "<td align='right'>".$this->language->get('column_coupon')."</td>" : '';
	isset($_POST['so29']) ? $export_pdf_order_list .= "<td align='right'>".$this->language->get('column_tax')."</td>" : '';
	isset($_POST['so30']) ? $export_pdf_order_list .= "<td align='right'>".$this->language->get('column_credit')."</td>" : '';
	isset($_POST['so31']) ? $export_pdf_order_list .= "<td align='right'>".$this->language->get('column_voucher')."</td>" : '';
	isset($_POST['so32']) ? $export_pdf_order_list .= "<td align='right'>".$this->language->get('column_commission')."</td>" : '';	
	isset($_POST['so33']) ? $export_pdf_order_list .= "<td align='right'>".$this->language->get('column_total')."</td>" : '';	
	$export_pdf_order_list .="</tr></thead>";
	$export_pdf_order_list .="<tbody><tr>";
	$export_pdf_order_list .= "<td colspan='2' align='right' style='background-color:#E7EFEF; font-weight:bold;'>".$this->language->get('text_filter_total')."</td>";
	isset($_POST['so20']) ? $export_pdf_order_list .= "<td align='right' nowrap='nowrap' class='total'>".$result['orders_total']."</td>" : '';
	isset($_POST['so21']) ? $export_pdf_order_list .= "<td align='right' nowrap='nowrap' class='total'>".$result['customers_total']."</td>" : '';
	isset($_POST['so22']) ? $export_pdf_order_list .= "<td align='right' nowrap='nowrap' class='total'>".$result['products_total']."</td>" : '';
	isset($_POST['so23']) ? $export_pdf_order_list .= "<td align='right' nowrap='nowrap' class='total'>".$this->currency->format($result['sub_total_total'], $this->config->get('config_currency'))."</td>" : '';
	isset($_POST['so24']) ? $export_pdf_order_list .= "<td align='right' nowrap='nowrap' class='total'>".$this->currency->format($result['handling_total'], $this->config->get('config_currency'))."</td>" : '';
	isset($_POST['so25']) ? $export_pdf_order_list .= "<td align='right' nowrap='nowrap' class='total'>".$this->currency->format($result['low_order_fee_total'], $this->config->get('config_currency'))."</td>" : '';
	isset($_POST['so27']) ? $export_pdf_order_list .= "<td align='right' nowrap='nowrap' class='total'>".$this->currency->format($result['shipping_total'], $this->config->get('config_currency'))."</td>" : '';
	isset($_POST['so26']) ? $export_pdf_order_list .= "<td align='right' nowrap='nowrap' class='total'>".$this->currency->format($result['reward_total'], $this->config->get('config_currency'))."</td>" : '';
	isset($_POST['so28']) ? $export_pdf_order_list .= "<td align='right' nowrap='nowrap' class='total'>".$this->currency->format($result['coupon_total'], $this->config->get('config_currency'))."</td>" : '';
	isset($_POST['so29']) ? $export_pdf_order_list .= "<td align='right' nowrap='nowrap' class='total'>".$this->currency->format($result['tax_total'], $this->config->get('config_currency'))."</td>" : '';
	isset($_POST['so30']) ? $export_pdf_order_list .= "<td align='right' nowrap='nowrap' class='total'>".$this->currency->format($result['credit_total'], $this->config->get('config_currency'))."</td>" : '';
	isset($_POST['so31']) ? $export_pdf_order_list .= "<td align='right' nowrap='nowrap' class='total'>".$this->currency->format($result['voucher_total'], $this->config->get('config_currency'))."</td>" : '';
	isset($_POST['so32']) ? $export_pdf_order_list .= "<td align='right' nowrap='nowrap' class='total'>".$this->currency->format('-' . ($result['commission_total']), $this->config->get('config_currency'))."</td>" : '';
	isset($_POST['so33']) ? $export_pdf_order_list .= "<td align='right' nowrap='nowrap' class='total'>".$this->currency->format($result['total_total'], $this->config->get('config_currency'))."</td>" : '';
	$export_pdf_order_list .="</tr></tbody></table>";
	$export_pdf_order_list .="</body></html>";

ini_set('mbstring.substitute_character', "none"); 
$dompdf_pdf_order_list = mb_convert_encoding($export_pdf_order_list, 'ISO-8859-1', 'UTF-8'); 
$dompdf = new DOMPDF();
$dompdf->load_html($dompdf_pdf_order_list);
$dompdf->set_paper("a3", "landscape");
$dompdf->render();
$dompdf->stream("sale_order_report_order_list_".date("Y-m-d",time()).".pdf");
?>
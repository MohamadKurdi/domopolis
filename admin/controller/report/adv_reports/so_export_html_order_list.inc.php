<?php
ini_set("memory_limit","256M");

	$export_html_order_list ="<html><head>";
	$export_html_order_list .="<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
	$export_html_order_list .="</head>";
	$export_html_order_list .="<body>";
	$export_html_order_list .="<style type='text/css'>
	.list_main {
		border-collapse: collapse;
		width: 100%;
		border-top: 1px solid #DDDDDD;
		border-left: 1px solid #DDDDDD;	
		font-family: Arial, Helvetica, sans-serif;
		font-size: 12px;
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
	
	.list_detail {
		border-collapse: collapse;
		width: 100%;
		border-top: 1px solid #DDDDDD;
		border-left: 1px solid #DDDDDD;
		font-family: Arial, Helvetica, sans-serif;	
		margin-top: 10px;
		margin-bottom: 10px;
	}
	.list_detail td {
		border-right: 1px solid #DDDDDD;
		border-bottom: 1px solid #DDDDDD;
	}
	.list_detail thead td {
		background-color: #F0F0F0;
		padding: 0px 3px;
		font-size: 10px;
		font-weight: bold;	
	}
	.list_detail tbody td {
		padding: 0px 3px;
		font-size: 10px;	
	}
	
	.total {
		background-color: #E7EFEF;
		color: #003A88;
		font-weight: bold;
	}		
	</style>";
	$export_html_order_list .="<table class='list_main'>";
	foreach ($results as $result) {	
	$export_html_order_list .="<thead>";
	$export_html_order_list .="<tr>";
	if ($filter_report == 'sales_summary') {
	if ($filter_group == 'year') {					
	$export_html_order_list .= "<td colspan='2' align='left' nowrap='nowrap'>".$this->language->get('column_year')."</td>";
	} elseif ($filter_group == 'quarter') {
	$export_html_order_list .= "<td align='left' nowrap='nowrap'>".$this->language->get('column_year')."</td>";
	$export_html_order_list .= "<td align='left' nowrap='nowrap'>".$this->language->get('column_quarter')."</td>";				
	} elseif ($filter_group == 'month') {
	$export_html_order_list .= "<td align='left' nowrap='nowrap'>".$this->language->get('column_year')."</td>";
	$export_html_order_list .= "<td align='left' nowrap='nowrap'>".$this->language->get('column_month')."</td>";
	} elseif ($filter_group == 'day') {
	$export_html_order_list .= "<td colspan='2' align='left' nowrap='nowrap'>".$this->language->get('column_date')."</td>";
	} elseif ($filter_group == 'order') {
	$export_html_order_list .= "<td align='left' nowrap='nowrap'>".$this->language->get('column_order_order_id')."</td>";
	$export_html_order_list .= "<td align='left' nowrap='nowrap'>".$this->language->get('column_order_date_added')."</td>";
	} else {
	$export_html_order_list .= "<td align='left' width='80' nowrap='nowrap'>".$this->language->get('column_date_start')."</td>";
	$export_html_order_list .= "<td align='left' width='80' nowrap='nowrap'>".$this->language->get('column_date_end')."</td>";	
	}
	} elseif ($filter_report == 'day_of_week') {
	$export_html_order_list .= "<td colspan='2' align='left' nowrap='nowrap'>".$this->language->get('column_day_of_week')."</td>";
	} elseif ($filter_report == 'hour') {
	$export_html_order_list .= "<td colspan='2' align='left' nowrap='nowrap'>".$this->language->get('column_hour')."</td>";
	} elseif ($filter_report == 'store') {
	$export_html_order_list .= "<td colspan='2' align='left' nowrap='nowrap'>".$this->language->get('column_store')."</td>";
	} elseif ($filter_report == 'customer_group') {
	$export_html_order_list .= "<td colspan='2' align='left' nowrap='nowrap'>".$this->language->get('column_customer_group')."</td>";
	} elseif ($filter_report == 'country') {
	$export_html_order_list .= "<td colspan='2' align='left' nowrap='nowrap'>".$this->language->get('column_country')."</td>";
	} elseif ($filter_report == 'postcode') {
	$export_html_order_list .= "<td colspan='2' align='left' nowrap='nowrap'>".$this->language->get('column_postcode')."</td>";
	} elseif ($filter_report == 'region_state') {
	$export_html_order_list .= "<td colspan='2' align='left' nowrap='nowrap'>".$this->language->get('column_region_state')."</td>";
	} elseif ($filter_report == 'city') {
	$export_html_order_list .= "<td colspan='2' align='left' nowrap='nowrap'>".$this->language->get('column_city')."</td>";
	} elseif ($filter_report == 'payment_method') {
	$export_html_order_list .= "<td colspan='2' align='left' nowrap='nowrap'>".$this->language->get('column_payment_method')."</td>";
	} elseif ($filter_report == 'shipping_method') {
	$export_html_order_list .= "<td colspan='2' align='left' nowrap='nowrap'>".$this->language->get('column_shipping_method')."</td>";	
	}	
	isset($_POST['so20']) ? $export_html_order_list .= "<td align='right'>".$this->language->get('column_orders')."</td>" : '';
	isset($_POST['so21']) ? $export_html_order_list .= "<td align='right'>".$this->language->get('column_customers')."</td>" : '';
	isset($_POST['so22']) ? $export_html_order_list .= "<td align='right'>".$this->language->get('column_products')."</td>" : '';
	isset($_POST['so23']) ? $export_html_order_list .= "<td align='right'>".$this->language->get('column_sub_total')."</td>" : '';
	isset($_POST['so24']) ? $export_html_order_list .= "<td align='right'>".$this->language->get('column_handling')."</td>" : '';
	isset($_POST['so25']) ? $export_html_order_list .= "<td align='right'>".$this->language->get('column_loworder')."</td>" : '';
	isset($_POST['so27']) ? $export_html_order_list .= "<td align='right'>".$this->language->get('column_shipping')."</td>" : '';	
	isset($_POST['so26']) ? $export_html_order_list .= "<td align='right'>".$this->language->get('column_reward')."</td>" : '';
	isset($_POST['so28']) ? $export_html_order_list .= "<td align='right'>".$this->language->get('column_coupon')."</td>" : '';
	isset($_POST['so29']) ? $export_html_order_list .= "<td align='right'>".$this->language->get('column_tax')."</td>" : '';
	isset($_POST['so30']) ? $export_html_order_list .= "<td align='right'>".$this->language->get('column_credit')."</td>" : '';
	isset($_POST['so31']) ? $export_html_order_list .= "<td align='right'>".$this->language->get('column_voucher')."</td>" : '';
	isset($_POST['so32']) ? $export_html_order_list .= "<td align='right'>".$this->language->get('column_commission')."</td>" : '';	
	isset($_POST['so33']) ? $export_html_order_list .= "<td align='right'>".$this->language->get('column_total')."</td>" : '';
	$export_html_order_list .="</tr>";
	$export_html_order_list .="</thead><tbody>";
	$export_html_order_list .="<tr>";
	if ($filter_report == 'sales_summary') {
	if ($filter_group == 'year') {					
	$export_html_order_list .= "<td colspan='2' align='left' nowrap='nowrap'>".$result['year']."</td>";
	} elseif ($filter_group == 'quarter') {
	$export_html_order_list .= "<td align='left' nowrap='nowrap'>".$result['year']."</td>";	
	$export_html_order_list .= "<td align='left' nowrap='nowrap'>".'Q' . $result['quarter']."</td>";						
	} elseif ($filter_group == 'month') {
	$export_html_order_list .= "<td align='left' nowrap='nowrap'>".$result['year']."</td>";	
	$export_html_order_list .= "<td align='left' nowrap='nowrap'>".$result['month']."</td>";	
	} elseif ($filter_group == 'day') {
	$export_html_order_list .= "<td colspan='2' align='left' nowrap='nowrap'>".date($this->language->get('date_format_short'), strtotime($result['date_start']))."</td>";
	} elseif ($filter_group == 'order') {
	$export_html_order_list .= "<td align='left' nowrap='nowrap'>".$result['order_id']."</td>";	
	$export_html_order_list .= "<td align='left' nowrap='nowrap'>".date($this->language->get('date_format_short'), strtotime($result['date_start']))."</td>";
	} else {
	$export_html_order_list .= "<td align='left' nowrap='nowrap'>".date($this->language->get('date_format_short'), strtotime($result['date_start']))."</td>";
	$export_html_order_list .= "<td align='left' nowrap='nowrap'>".date($this->language->get('date_format_short'), strtotime($result['date_end']))."</td>";
	}
	} elseif ($filter_report == 'day_of_week') {
	$export_html_order_list .= "<td colspan='2' align='left' nowrap='nowrap' style='background-color:#F0F0F0;'>".$result['day_of_week']."</td>";
	} elseif ($filter_report == 'hour') {
	$export_html_order_list .= "<td colspan='2' align='left' nowrap='nowrap' style='background-color:#F0F0F0;'>".number_format($result['hour'], 2, ':', '')."</td>";
	} elseif ($filter_report == 'store') {
	$export_html_order_list .= "<td colspan='2' align='left' nowrap='nowrap' style='background-color:#F0F0F0;'>".html_entity_decode($result['store_name'])."</td>";
	} elseif ($filter_report == 'customer_group') {
	$export_html_order_list .= "<td colspan='2' align='left' nowrap='nowrap' style='background-color:#F0F0F0;'>".html_entity_decode($result['customer_group'])."</td>";
	} elseif ($filter_report == 'country') {
	$export_html_order_list .= "<td colspan='2' align='left' nowrap='nowrap' style='background-color:#F0F0F0;'>".$result['payment_country']."</td>";
	} elseif ($filter_report == 'postcode') {
	$export_html_order_list .= "<td colspan='2' align='left' nowrap='nowrap' style='background-color:#F0F0F0;'>".$result['payment_postcode']."</td>";
	} elseif ($filter_report == 'region_state') {
	$export_html_order_list .= "<td colspan='2' align='left' nowrap='nowrap' style='background-color:#F0F0F0;'>".$result['payment_zone']. ', ' . $result['payment_country']."</td>";
	} elseif ($filter_report == 'city') {
	$export_html_order_list .= "<td colspan='2' align='left' nowrap='nowrap' style='background-color:#F0F0F0;'>".$result['payment_city']. ', ' . $result['payment_country']."</td>";
	} elseif ($filter_report == 'payment_method') {
	$export_html_order_list .= "<td colspan='2' align='left' nowrap='nowrap' style='background-color:#F0F0F0;'>".preg_replace('~\(.*?\)~', '', $result['payment_method'])."</td>";
	} elseif ($filter_report == 'shipping_method') {
	$export_html_order_list .= "<td colspan='2' align='left' nowrap='nowrap' style='background-color:#F0F0F0;'>".preg_replace('~\(.*?\)~', '', $result['shipping_method'])."</td>";
	}
	isset($_POST['so20']) ? $export_html_order_list .= "<td align='right' nowrap='nowrap'>".$result['orders']."</td>" : '';
	isset($_POST['so21']) ? $export_html_order_list .= "<td align='right' nowrap='nowrap'>".$result['customers']."</td>" : '';
	isset($_POST['so22']) ? $export_html_order_list .= "<td align='right' nowrap='nowrap'>".$result['products']."</td>" : '';
	isset($_POST['so23']) ? $export_html_order_list .= "<td align='right' nowrap='nowrap'>".$this->currency->format($result['sub_total'], $this->config->get('config_currency'))."</td>" : '';
	isset($_POST['so24']) ? $export_html_order_list .= "<td align='right' nowrap='nowrap'>".$this->currency->format($result['handling'], $this->config->get('config_currency'))."</td>" : '';
	isset($_POST['so25']) ? $export_html_order_list .= "<td align='right' nowrap='nowrap'>".$this->currency->format($result['low_order_fee'], $this->config->get('config_currency'))."</td>" : '';
	isset($_POST['so27']) ? $export_html_order_list .= "<td align='right' nowrap='nowrap'>".$this->currency->format($result['shipping'], $this->config->get('config_currency'))."</td>" : '';	
	isset($_POST['so26']) ? $export_html_order_list .= "<td align='right' nowrap='nowrap'>".$this->currency->format($result['reward'], $this->config->get('config_currency'))."</td>" : '';
	isset($_POST['so28']) ? $export_html_order_list .= "<td align='right' nowrap='nowrap'>".$this->currency->format($result['coupon'], $this->config->get('config_currency'))."</td>" : '';
	isset($_POST['so29']) ? $export_html_order_list .= "<td align='right' nowrap='nowrap'>".$this->currency->format($result['tax'], $this->config->get('config_currency'))."</td>" : '';
	isset($_POST['so30']) ? $export_html_order_list .= "<td align='right' nowrap='nowrap'>".$this->currency->format($result['credit'], $this->config->get('config_currency'))."</td>" : '';
	isset($_POST['so31']) ? $export_html_order_list .= "<td align='right' nowrap='nowrap'>".$this->currency->format($result['voucher'], $this->config->get('config_currency'))."</td>" : '';
	isset($_POST['so32']) ? $export_html_order_list .= "<td align='right' nowrap='nowrap'>".$this->currency->format('-' . ($result['commission']), $this->config->get('config_currency'))."</td>" : '';
	isset($_POST['so33']) ? $export_html_order_list .= "<td align='right' nowrap='nowrap'>".$this->currency->format($result['total'], $this->config->get('config_currency'))."</td>" : '';					
	$export_html_order_list .="</tr>";
	$export_html_order_list .="<tr>";
	$count = isset($_POST['so20'])+isset($_POST['so21'])+isset($_POST['so22'])+isset($_POST['so23'])+isset($_POST['so24'])+isset($_POST['so25'])+isset($_POST['so27'])+isset($_POST['so26'])+isset($_POST['so28'])+isset($_POST['so29'])+isset($_POST['so30'])+isset($_POST['so31'])+isset($_POST['so32'])+isset($_POST['so33'])+2;
	$export_html_order_list .= "<td colspan='";
	$export_html_order_list .= $count;
	$export_html_order_list .="' align='center'>";
		$export_html_order_list .="<table class='list_detail'>";
		$export_html_order_list .="<thead>";
		$export_html_order_list .="<tr>";
		isset($_POST['so40']) ? $export_html_order_list .= "<td align='left'>".$this->language->get('column_order_order_id')."</td>" : '';
		isset($_POST['so41']) ? $export_html_order_list .= "<td align='left'>".$this->language->get('column_order_date_added')."</td>" : '';
		isset($_POST['so42']) ? $export_html_order_list .= "<td align='left'>".$this->language->get('column_order_inv_no')."</td>" : '';
		isset($_POST['so43']) ? $export_html_order_list .= "<td align='left'>".$this->language->get('column_order_customer')."</td>" : '';
		isset($_POST['so44']) ? $export_html_order_list .= "<td align='left'>".$this->language->get('column_order_email')."</td>" : '';
		isset($_POST['so45']) ? $export_html_order_list .= "<td align='left'>".$this->language->get('column_order_customer_group')."</td>" : '';
		isset($_POST['so46']) ? $export_html_order_list .= "<td align='left'>".$this->language->get('column_order_shipping_method')."</td>" : '';
		isset($_POST['so47']) ? $export_html_order_list .= "<td align='left'>".$this->language->get('column_order_payment_method')."</td>" : '';
		isset($_POST['so48']) ? $export_html_order_list .= "<td align='left'>".$this->language->get('column_order_status')."</td>" : '';
		isset($_POST['so49']) ? $export_html_order_list .= "<td align='left'>".$this->language->get('column_order_store')."</td>" : '';
		isset($_POST['so50']) ? $export_html_order_list .= "<td align='right'>".$this->language->get('column_order_currency')."</td>" : '';
		isset($_POST['so51']) ? $export_html_order_list .= "<td align='right'>".$this->language->get('column_order_quantity')."</td>" : '';
		isset($_POST['so52']) ? $export_html_order_list .= "<td align='right'>".$this->language->get('column_order_sub_total')."</td>" : '';
		isset($_POST['so54']) ? $export_html_order_list .= "<td align='right'>".$this->language->get('column_order_shipping')."</td>" : '';
		isset($_POST['so55']) ? $export_html_order_list .= "<td align='right'>".$this->language->get('column_order_tax')."</td>" : '';
		isset($_POST['so56']) ? $export_html_order_list .= "<td align='right'>".$this->language->get('column_order_value')."</td>" : '';
		$export_html_order_list .="</tr>";
		$export_html_order_list .="</thead><tbody>";
		$export_html_order_list .="<tr>";
		isset($_POST['so40']) ? $export_html_order_list .= "<td align='left' nowrap='nowrap'>".$result['order_ord_idc']."</td>" : '';
		isset($_POST['so41']) ? $export_html_order_list .= "<td align='left' nowrap='nowrap'>".$result['order_order_date']."</td>" : '';
		isset($_POST['so42']) ? $export_html_order_list .= "<td align='left' nowrap='nowrap'>".$result['order_inv_no']."</td>" : '';
		isset($_POST['so43']) ? $export_html_order_list .= "<td align='left' nowrap='nowrap'>".$result['order_name']."</td>" : '';
		isset($_POST['so44']) ? $export_html_order_list .= "<td align='left' nowrap='nowrap'>".$result['order_email']."</td>" : '';
		isset($_POST['so45']) ? $export_html_order_list .= "<td align='left' nowrap='nowrap'>".$result['order_group']."</td>" : '';
		isset($_POST['so46']) ? $export_html_order_list .= "<td align='left' nowrap='nowrap'>".strip_tags($result['order_shipping_method'], '<br>')."</td>" : '';
		isset($_POST['so47']) ? $export_html_order_list .= "<td align='left' nowrap='nowrap'>".strip_tags($result['order_payment_method'], '<br>')."</td>" : '';
		isset($_POST['so48']) ? $export_html_order_list .= "<td align='left' nowrap='nowrap'>".$result['order_status']."</td>" : '';
		isset($_POST['so49']) ? $export_html_order_list .= "<td align='left' nowrap='nowrap'>".$result['order_store']."</td>" : '';
		isset($_POST['so50']) ? $export_html_order_list .= "<td align='right' nowrap='nowrap'>".$result['order_currency']."</td>" : '';
		isset($_POST['so51']) ? $export_html_order_list .= "<td align='right' nowrap='nowrap'>".$result['order_products']."</td>" : '';
		isset($_POST['so52']) ? $export_html_order_list .= "<td align='right' nowrap='nowrap'>".$result['order_sub_total']."</td>" : '';
		isset($_POST['so54']) ? $export_html_order_list .= "<td align='right' nowrap='nowrap'>".$result['order_shipping']."</td>" : '';
		isset($_POST['so55']) ? $export_html_order_list .= "<td align='right' nowrap='nowrap'>".$result['order_tax']."</td>" : '';
		isset($_POST['so56']) ? $export_html_order_list .= "<td align='right' nowrap='nowrap'>".$result['order_value']."</td>" : '';	
		$export_html_order_list .="</tr>";					
		$export_html_order_list .="</tbody></table>";
	$export_html_order_list .="</td>";
	$export_html_order_list .="</tr>";
	}
	$export_html_order_list .="</tbody>";
	$export_html_order_list .="<thead><tr>";	
	$export_html_order_list .= "<td colspan='2'></td>";
	isset($_POST['so20']) ? $export_html_order_list .= "<td align='right'>".$this->language->get('column_orders')."</td>" : '';
	isset($_POST['so21']) ? $export_html_order_list .= "<td align='right'>".$this->language->get('column_customers')."</td>" : '';
	isset($_POST['so22']) ? $export_html_order_list .= "<td align='right'>".$this->language->get('column_products')."</td>" : '';
	isset($_POST['so23']) ? $export_html_order_list .= "<td align='right'>".$this->language->get('column_sub_total')."</td>" : '';
	isset($_POST['so24']) ? $export_html_order_list .= "<td align='right'>".$this->language->get('column_handling')."</td>" : '';
	isset($_POST['so25']) ? $export_html_order_list .= "<td align='right'>".$this->language->get('column_loworder')."</td>" : '';
	isset($_POST['so27']) ? $export_html_order_list .= "<td align='right'>".$this->language->get('column_shipping')."</td>" : '';	
	isset($_POST['so26']) ? $export_html_order_list .= "<td align='right'>".$this->language->get('column_reward')."</td>" : '';
	isset($_POST['so28']) ? $export_html_order_list .= "<td align='right'>".$this->language->get('column_coupon')."</td>" : '';
	isset($_POST['so29']) ? $export_html_order_list .= "<td align='right'>".$this->language->get('column_tax')."</td>" : '';
	isset($_POST['so30']) ? $export_html_order_list .= "<td align='right'>".$this->language->get('column_credit')."</td>" : '';
	isset($_POST['so31']) ? $export_html_order_list .= "<td align='right'>".$this->language->get('column_voucher')."</td>" : '';
	isset($_POST['so32']) ? $export_html_order_list .= "<td align='right'>".$this->language->get('column_commission')."</td>" : '';	
	isset($_POST['so33']) ? $export_html_order_list .= "<td align='right'>".$this->language->get('column_total')."</td>" : '';
	$export_html_order_list .="</tr></thead>";
	$export_html_order_list .="<tbody><tr>";	
	$export_html_order_list .= "<td colspan='2' align='right' style='background-color:#E7EFEF; font-weight:bold;'>".$this->language->get('text_filter_total')."</td>";
	isset($_POST['so20']) ? $export_html_order_list .= "<td align='right' nowrap='nowrap' class='total'>".$result['orders_total']."</td>" : '';
	isset($_POST['so21']) ? $export_html_order_list .= "<td align='right' nowrap='nowrap' class='total'>".$result['customers_total']."</td>" : '';
	isset($_POST['so22']) ? $export_html_order_list .= "<td align='right' nowrap='nowrap' class='total'>".$result['products_total']."</td>" : '';
	isset($_POST['so23']) ? $export_html_order_list .= "<td align='right' nowrap='nowrap' class='total'>".$this->currency->format($result['sub_total_total'], $this->config->get('config_currency'))."</td>" : '';
	isset($_POST['so24']) ? $export_html_order_list .= "<td align='right' nowrap='nowrap' class='total'>".$this->currency->format($result['handling_total'], $this->config->get('config_currency'))."</td>" : '';
	isset($_POST['so25']) ? $export_html_order_list .= "<td align='right' nowrap='nowrap' class='total'>".$this->currency->format($result['low_order_fee_total'], $this->config->get('config_currency'))."</td>" : '';
	isset($_POST['so27']) ? $export_html_order_list .= "<td align='right' nowrap='nowrap' class='total'>".$this->currency->format($result['shipping_total'], $this->config->get('config_currency'))."</td>" : '';
	isset($_POST['so26']) ? $export_html_order_list .= "<td align='right' nowrap='nowrap' class='total'>".$this->currency->format($result['reward_total'], $this->config->get('config_currency'))."</td>" : '';
	isset($_POST['so28']) ? $export_html_order_list .= "<td align='right' nowrap='nowrap' class='total'>".$this->currency->format($result['coupon_total'], $this->config->get('config_currency'))."</td>" : '';
	isset($_POST['so29']) ? $export_html_order_list .= "<td align='right' nowrap='nowrap' class='total'>".$this->currency->format($result['tax_total'], $this->config->get('config_currency'))."</td>" : '';
	isset($_POST['so30']) ? $export_html_order_list .= "<td align='right' nowrap='nowrap' class='total'>".$this->currency->format($result['credit_total'], $this->config->get('config_currency'))."</td>" : '';
	isset($_POST['so31']) ? $export_html_order_list .= "<td align='right' nowrap='nowrap' class='total'>".$this->currency->format($result['voucher_total'], $this->config->get('config_currency'))."</td>" : '';
	isset($_POST['so32']) ? $export_html_order_list .= "<td align='right' nowrap='nowrap' class='total'>".$this->currency->format('-' . ($result['commission_total']), $this->config->get('config_currency'))."</td>" : '';	
	isset($_POST['so33']) ? $export_html_order_list .= "<td align='right' nowrap='nowrap' class='total'>".$this->currency->format($result['total_total'], $this->config->get('config_currency'))."</td>" : '';
	$export_html_order_list .="</tr></tbody></table>";
	$export_html_order_list .="</body></html>";

$filename = "sale_order_report_order_list_".date("Y-m-d",time());
header('Expires: 0');
header('Cache-control: private');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Content-Description: File Transfer');			
header('Content-Disposition: attachment; filename='.$filename.".html");
print $export_html_order_list;			
exit;			
?>
<?php
ini_set("memory_limit","256M");

	$export_pdf_all_details = "<html><head>";
	$export_pdf_all_details .= "</head>";
	$export_pdf_all_details .= "<body>";
	$export_pdf_all_details .= "<style type='text/css'>
	.list_detail {
		width: 100%;
		font-family: Helvetica;
		padding: 3px;		
	}
	.list_detail thead td {
		border: 1px solid #DDDDDD;		
		background-color: #F0F0F0;
		padding: 0px 3px;
		font-size: 10px;
		font-weight: bold;
	}	
	.list_detail tbody td {
		border: 1px solid #DDDDDD;
		padding: 0px 3px;
		font-size: 10px;	
	}
	</style>";
	foreach ($results as $result) {	
	if ($result['product_pidc']) {	
	$export_pdf_all_details .= "<div style='border:1px solid #999; margin-bottom:10px; width:100%; page-break-inside:avoid'>";
	$export_pdf_all_details .= "<table cellspacing='0' cellpadding='0' class='list_detail'>";
	$export_pdf_all_details .= "<thead><tr>";	
	$export_pdf_all_details .= "<td align='left' nowrap='nowrap'>".$this->language->get('column_order_order_id')."</td>";
	$export_pdf_all_details .= "<td align='left' nowrap='nowrap'>".$this->language->get('column_order_date_added')."</td>";
	isset($_POST['so1000']) ? $export_pdf_all_details .= "<td align='left'>".$this->language->get('column_order_inv_no')."</td>" : '';
	isset($_POST['so1001']) ? $export_pdf_all_details .= "<td align='left'>".$this->language->get('column_order_customer')."</td>" : '';	
	isset($_POST['so1002']) ? $export_pdf_all_details .= "<td align='left'>".$this->language->get('column_order_email')."</td>" : '';
	isset($_POST['so1003']) ? $export_pdf_all_details .= "<td align='left'>".$this->language->get('column_order_customer_group')."</td>" : '';
	isset($_POST['so1040']) ? $export_pdf_all_details .= "<td align='left'>".$this->language->get('column_order_shipping_method')."</td>" : '';
	isset($_POST['so1041']) ? $export_pdf_all_details .= "<td align='left'>".$this->language->get('column_order_payment_method')."</td>" : '';
	isset($_POST['so1042']) ? $export_pdf_all_details .= "<td align='left'>".$this->language->get('column_order_status')."</td>" : '';
	isset($_POST['so1043']) ? $export_pdf_all_details .= "<td align='left'>".$this->language->get('column_order_store')."</td>" : '';
	isset($_POST['so1012']) ? $export_pdf_all_details .= "<td align='right'>".$this->language->get('column_order_currency')."</td>" : '';
	isset($_POST['so1062']) ? $export_pdf_all_details .= "<td align='right'>".$this->language->get('column_order_quantity')."</td>" : '';	
	isset($_POST['so1020']) ? $export_pdf_all_details .= "<td align='right'>".$this->language->get('column_order_sub_total')."</td>" : '';
	isset($_POST['so1023']) ? $export_pdf_all_details .= "<td align='right'>".$this->language->get('column_order_shipping')."</td>" : '';
	isset($_POST['so1027']) ? $export_pdf_all_details .= "<td align='right'>".$this->language->get('column_order_tax')."</td>" : '';
	isset($_POST['so1031']) ? $export_pdf_all_details .= "<td align='right'>".$this->language->get('column_order_value')."</td>" : '';
	$export_pdf_all_details .="</tr></thead>";
	$export_pdf_all_details .="<tbody><tr>";
	$export_pdf_all_details .= "<td align='left' nowrap='nowrap' style='background-color:#FFC;'>".$result['order_ord_idc']."</td>";
	$export_pdf_all_details .= "<td align='left' nowrap='nowrap' style='background-color:#FFC;'>".$result['order_order_date']."</td>";
	isset($_POST['so1000']) ? $export_pdf_all_details .= "<td align='left' nowrap='nowrap'>".$result['order_inv_no']."</td>" : '';
	isset($_POST['so1001']) ? $export_pdf_all_details .= "<td align='left' nowrap='nowrap'>".$result['order_name']."</td>" : '';	
	isset($_POST['so1002']) ? $export_pdf_all_details .= "<td align='left' nowrap='nowrap'>".$result['order_email']."</td>" : '';
	isset($_POST['so1003']) ? $export_pdf_all_details .= "<td align='left' nowrap='nowrap'>".$result['order_group']."</td>" : '';
	isset($_POST['so1040']) ? $export_pdf_all_details .= "<td align='left' nowrap='nowrap'>".strip_tags($result['order_shipping_method'], '<br>')."</td>" : '';
	isset($_POST['so1041']) ? $export_pdf_all_details .= "<td align='left' nowrap='nowrap'>".strip_tags($result['order_payment_method'], '<br>')."</td>" : '';
	isset($_POST['so1042']) ? $export_pdf_all_details .= "<td align='left' nowrap='nowrap'>".$result['order_status']."</td>" : '';
	isset($_POST['so1043']) ? $export_pdf_all_details .= "<td align='left' nowrap='nowrap'>".$result['order_store']."</td>" : '';
	isset($_POST['so1012']) ? $export_pdf_all_details .= "<td align='right' nowrap='nowrap'>".$result['order_currency']."</td>" : '';
	isset($_POST['so1062']) ? $export_pdf_all_details .= "<td align='right' nowrap='nowrap'>".$result['order_products']."</td>" : '';	
	isset($_POST['so1020']) ? $export_pdf_all_details .= "<td align='right' nowrap='nowrap'>".$result['order_sub_total']."</td>" : '';
	isset($_POST['so1023']) ? $export_pdf_all_details .= "<td align='right' nowrap='nowrap'>".$result['order_shipping']."</td>" : '';
	isset($_POST['so1027']) ? $export_pdf_all_details .= "<td align='right' nowrap='nowrap'>".$result['order_tax']."</td>" : '';
	isset($_POST['so1031']) ? $export_pdf_all_details .= "<td align='right' nowrap='nowrap'>".$result['order_value']."</td>" : '';
	$export_pdf_all_details .="</tr></tbody></table>";	
	$export_pdf_all_details .="<table cellspacing='0' cellpadding='0' class='list_detail'>";
	$export_pdf_all_details .="<thead><tr>";
	isset($_POST['so1004']) ? $export_pdf_all_details .= "<td align='left'>".$this->language->get('column_prod_id')."</td>" : '';
	isset($_POST['so1005']) ? $export_pdf_all_details .= "<td align='left'>".$this->language->get('column_prod_sku')."</td>" : '';
	isset($_POST['so1006']) ? $export_pdf_all_details .= "<td align='left'>".$this->language->get('column_prod_model')."</td>" : '';	
	isset($_POST['so1007']) ? $export_pdf_all_details .= "<td align='left'>".$this->language->get('column_prod_name')."</td>" : '';
	isset($_POST['so1008']) ? $export_pdf_all_details .= "<td align='left'>".$this->language->get('column_prod_option')."</td>" : '';
	isset($_POST['so1009']) ? $export_pdf_all_details .= "<td align='left'>".$this->language->get('column_prod_attributes')."</td>" : '';	
	isset($_POST['so1010']) ? $export_pdf_all_details .= "<td align='left'>".$this->language->get('column_prod_manu')."</td>" : '';	
	isset($_POST['so1011']) ? $export_pdf_all_details .= "<td align='left'>".$this->language->get('column_prod_category')."</td>" : '';		
	isset($_POST['so1013']) ? $export_pdf_all_details .= "<td align='right'>".$this->language->get('column_prod_price')."</td>" : '';
	isset($_POST['so1014']) ? $export_pdf_all_details .= "<td align='right'>".$this->language->get('column_prod_quantity')."</td>" : '';
	isset($_POST['so1016a']) ? $export_pdf_all_details .= "<td align='right'>".$this->language->get('column_prod_total_excl_vat')."</td>" : '';	
	isset($_POST['so1015']) ? $export_pdf_all_details .= "<td align='right'>".$this->language->get('column_prod_tax')."</td>" : '';		
	isset($_POST['so1016b']) ? $export_pdf_all_details .= "<td align='right'>".$this->language->get('column_prod_total_incl_vat')."</td>" : '';
	$export_pdf_all_details .="</tr></thead>";
	$export_pdf_all_details .="<tbody><tr>";
	isset($_POST['so1004']) ? $export_pdf_all_details .= "<td align='left' nowrap='nowrap'>".$result['product_pidc']."</td>" : '';
	isset($_POST['so1005']) ? $export_pdf_all_details .= "<td align='left' nowrap='nowrap'>".$result['product_sku']."</td>" : '';
	isset($_POST['so1006']) ? $export_pdf_all_details .= "<td align='left' nowrap='nowrap'>".$result['product_model']."</td>" : '';	
	isset($_POST['so1007']) ? $export_pdf_all_details .= "<td align='left' nowrap='nowrap'>".$result['product_name']."</td>" : '';
	isset($_POST['so1008']) ? $export_pdf_all_details .= "<td align='left' nowrap='nowrap'>".$result['product_option']."</td>" : '';
	isset($_POST['so1009']) ? $export_pdf_all_details .= "<td align='left' nowrap='nowrap'>".$result['product_attributes']."</td>" : '';
	isset($_POST['so1010']) ? $export_pdf_all_details .= "<td align='left' nowrap='nowrap'>".$result['product_manu']."</td>" : '';	
	isset($_POST['so1011']) ? $export_pdf_all_details .= "<td align='left' nowrap='nowrap'>".$result['product_category']."</td>" : '';
	isset($_POST['so1013']) ? $export_pdf_all_details .= "<td align='right' nowrap='nowrap'>".$result['product_price']."</td>" : '';
	isset($_POST['so1014']) ? $export_pdf_all_details .= "<td align='right' nowrap='nowrap'>".$result['product_quantity']."</td>" : '';
	isset($_POST['so1016a']) ? $export_pdf_all_details .= "<td align='right' nowrap='nowrap'>".$result['product_total_excl_vat']."</td>" : '';	
	isset($_POST['so1015']) ? $export_pdf_all_details .= "<td align='right' nowrap='nowrap'>".$result['product_tax']."</td>" : '';	
	isset($_POST['so1016b']) ? $export_pdf_all_details .= "<td align='right' nowrap='nowrap'>".$result['product_total_incl_vat']."</td>" : '';
	$export_pdf_all_details .="</tr></tbody></table>";		
	$export_pdf_all_details .="<table cellspacing='0' cellpadding='0' class='list_detail'>";
	$export_pdf_all_details .="<thead><tr>";
	isset($_POST['so1044']) ? $export_pdf_all_details .= "<td align='left'>".strip_tags($this->language->get('column_customer_cust_id'))."</td>" : '';
	isset($_POST['so1045']) ? $export_pdf_all_details .= "<td align='left'>".strip_tags($this->language->get('column_billing_name'))."</td>" : '';	
	isset($_POST['so1046']) ? $export_pdf_all_details .= "<td align='left'>".strip_tags($this->language->get('column_billing_company'))."</td>" : '';
	isset($_POST['so1047']) ? $export_pdf_all_details .= "<td align='left'>".strip_tags($this->language->get('column_billing_address_1'))."</td>" : '';
	isset($_POST['so1048']) ? $export_pdf_all_details .= "<td align='left'>".strip_tags($this->language->get('column_billing_address_2'))."</td>" : '';
	isset($_POST['so1049']) ? $export_pdf_all_details .= "<td align='left'>".strip_tags($this->language->get('column_billing_city'))."</td>" : '';
	isset($_POST['so1050']) ? $export_pdf_all_details .= "<td align='left'>".strip_tags($this->language->get('column_billing_zone'))."</td>" : '';
	isset($_POST['so1051']) ? $export_pdf_all_details .= "<td align='left'>".strip_tags($this->language->get('column_billing_postcode'))."</td>" : '';
	isset($_POST['so1052']) ? $export_pdf_all_details .= "<td align='left'>".strip_tags($this->language->get('column_billing_country'))."</td>" : '';
	isset($_POST['so1053']) ? $export_pdf_all_details .= "<td align='left'>".$this->language->get('column_customer_telephone')."</td>" : '';
	isset($_POST['so1054']) ? $export_pdf_all_details .= "<td align='left'>".strip_tags($this->language->get('column_shipping_name'))."</td>" : '';
	isset($_POST['so1055']) ? $export_pdf_all_details .= "<td align='left'>".strip_tags($this->language->get('column_shipping_company'))."</td>" : '';
	isset($_POST['so1056']) ? $export_pdf_all_details .= "<td align='left'>".strip_tags($this->language->get('column_shipping_address_1'))."</td>" : '';
	isset($_POST['so1057']) ? $export_pdf_all_details .= "<td align='left'>".strip_tags($this->language->get('column_shipping_address_2'))."</td>" : '';
	isset($_POST['so1058']) ? $export_pdf_all_details .= "<td align='left'>".strip_tags($this->language->get('column_shipping_city'))."</td>" : '';
	isset($_POST['so1059']) ? $export_pdf_all_details .= "<td align='left'>".strip_tags($this->language->get('column_shipping_zone'))."</td>" : '';
	isset($_POST['so1060']) ? $export_pdf_all_details .= "<td align='left'>".strip_tags($this->language->get('column_shipping_postcode'))."</td>" : '';
	isset($_POST['so1061']) ? $export_pdf_all_details .= "<td align='left'>".strip_tags($this->language->get('column_shipping_country'))."</td>" : '';	
	$export_pdf_all_details .="</tr></thead>";
	$export_pdf_all_details .="<tbody><tr>";
	isset($_POST['so1044']) ? $export_pdf_all_details .= "<td align='left' nowrap='nowrap'>".$result['customer_cust_idc']."</td>" : '';
	isset($_POST['so1045']) ? $export_pdf_all_details .= "<td align='left' nowrap='nowrap'>".$result['billing_name']."</td>" : '';
	isset($_POST['so1046']) ? $export_pdf_all_details .= "<td align='left' nowrap='nowrap'>".$result['billing_company']."</td>" : '';
	isset($_POST['so1047']) ? $export_pdf_all_details .= "<td align='left' nowrap='nowrap'>".$result['billing_address_1']."</td>" : '';
	isset($_POST['so1048']) ? $export_pdf_all_details .= "<td align='left' nowrap='nowrap'>".$result['billing_address_2']."</td>" : '';
	isset($_POST['so1049']) ? $export_pdf_all_details .= "<td align='left' nowrap='nowrap'>".$result['billing_city']."</td>" : '';
	isset($_POST['so1050']) ? $export_pdf_all_details .= "<td align='left' nowrap='nowrap'>".$result['billing_zone']."</td>" : '';
	isset($_POST['so1051']) ? $export_pdf_all_details .= "<td align='left' nowrap='nowrap'>".$result['billing_postcode']."</td>" : '';
	isset($_POST['so1052']) ? $export_pdf_all_details .= "<td align='left' nowrap='nowrap'>".$result['billing_country']."</td>" : '';
	isset($_POST['so1053']) ? $export_pdf_all_details .= "<td align='left' nowrap='nowrap'>".$result['customer_telephone']."</td>" : '';
	isset($_POST['so1054']) ? $export_pdf_all_details .= "<td align='left' nowrap='nowrap'>".$result['shipping_name']."</td>" : '';
	isset($_POST['so1055']) ? $export_pdf_all_details .= "<td align='left' nowrap='nowrap'>".$result['shipping_company']."</td>" : '';
	isset($_POST['so1056']) ? $export_pdf_all_details .= "<td align='left' nowrap='nowrap'>".$result['shipping_address_1']."</td>" : '';
	isset($_POST['so1057']) ? $export_pdf_all_details .= "<td align='left' nowrap='nowrap'>".$result['shipping_address_2']."</td>" : '';
	isset($_POST['so1058']) ? $export_pdf_all_details .= "<td align='left' nowrap='nowrap'>".$result['shipping_city']."</td>" : '';
	isset($_POST['so1059']) ? $export_pdf_all_details .= "<td align='left' nowrap='nowrap'>".$result['shipping_zone']."</td>" : '';
	isset($_POST['so1060']) ? $export_pdf_all_details .= "<td align='left' nowrap='nowrap'>".$result['shipping_postcode']."</td>" : '';
	isset($_POST['so1061']) ? $export_pdf_all_details .= "<td align='left' nowrap='nowrap'>".$result['shipping_country']."</td>" : '';	
	$export_pdf_all_details .="</tr></tbody></table>";
	$export_pdf_all_details .="</div>";	
	}
	}
	$export_pdf_all_details .="</body></html>";

ini_set('mbstring.substitute_character', "none"); 
$dompdf_pdf_all_details = mb_convert_encoding($export_pdf_all_details, 'ISO-8859-1', 'UTF-8'); 
$dompdf = new DOMPDF();
$dompdf->load_html($dompdf_pdf_all_details);
$dompdf->set_paper("a3", "landscape");
$dompdf->render();
$dompdf->stream("sale_order_report_all_details_".date("Y-m-d",time()).".pdf");
?>
<?php echo $header; ?>
<?php if (!defined('_JEXEC')) { ?>
<script type="text/javascript">
$(document).ready(function() { 
  $("#content_report").hide(); 
  $(window).load(function() { 
    	$("#content_report").show(); 
    	$("#content-loading").hide(); 
  	});
});
</script>
<div id="content-loading" style="position: absolute; background-color:white; layer-background-color:white; height:100%; width:100%; text-align:center;"><img src="view/image/adv_reports/page_loading.gif" border="0"></div>
<?php } ?>
<style type="text/css">
.box > .content_report {
	padding: 10px;
	border-left: 1px solid #CCCCCC;
	border-right: 1px solid #CCCCCC;
	border-bottom: 1px solid #CCCCCC;
	min-height: 300px;
}
.list_main {
	border-collapse: collapse;
	width: 100%;
	border-top: 1px solid #DDDDDD;
	border-left: 1px solid #DDDDDD;	
	margin-bottom: 10px;
}
.list_main td {
	border-right: 1px solid #DDDDDD;
	border-bottom: 1px solid #DDDDDD;	
}
.list_main thead td {
	background-color: #E5E5E5;
	padding: 0px 5px;
	font-weight: bold;	
}
.list_main tbody td {
	vertical-align: middle;
	padding: 0px 5px;
}
.list_main .left {
	text-align: left;
	padding: 7px;
}
.list_main .right {
	text-align: right;
	padding: 7px;
}
.list_main .center {
	text-align: center;
	padding: 3px;
}
.list_main .noresult {
	text-align: center;
	padding: 7px;
}

.list_detail {
	border-collapse: collapse;
	width: 100%;
	border-top: 1px solid #DDDDDD;
	border-left: 1px solid #DDDDDD;
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
	font-size: 11px;
	font-weight: bold;
}
.list_detail tbody td {
	padding: 0px 3px;
	font-size: 11px;	
}
.list_detail .left {
	text-align: left;
	padding: 3px;
}
.list_detail .right {
	text-align: right;
	padding: 3px;
}
.list_detail .center {
	text-align: center;
	padding: 3px;
}

.columns_setting {
	float: left; 
	margin: 1px;
	padding: 1px;
	padding-right: 3px; 	
	border: thin dotted #666;
    -moz-border-radius: 3px; 
    border-radius: 3px;	
}
.set_unchecked {
	background-color: #ffcc99;
}

.export_item {
  text-decoration: none;
  cursor: pointer;
}
.export_item a {
  text-decoration: none;
}
.export_item :hover {
  opacity: 0.7;
  -moz-opacity: 0.7;
  -ms-filter: "alpha(opacity=70)"; /* IE 8 */
  filter: alpha(opacity=70); /* IE < 8 */
} 
.noexport_item {
  opacity: 0.5;
  -moz-opacity: 0.5;
  -ms-filter: "alpha(opacity=50)"; /* IE 8 */
  filter: alpha(opacity=50); /* IE < 8 */
} 

a.cbutton {
	text-decoration: none;
	color: #FFF;
	display: inline-block;
	padding: 5px 15px 5px 15px;
	-webkit-border-radius: 5px 5px 5px 5px;
	-moz-border-radius: 5px 5px 5px 5px;
	-khtml-border-radius: 5px 5px 5px 5px;
	border-radius: 5px 5px 5px 5px;
}

.pagination_report {
	padding:3px;
	margin:3px;
	text-align:right;
	margin-top:10px;
}
.pagination_report a {
	padding: 4px 8px 4px 8px;
	margin-right: 2px;
	border: 1px solid #ddd;
	text-decoration: none; 
	color: #666;
}
.pagination_report a:hover, .pagination_report a:active {
	padding: 4px 8px 4px 8px;
	margin-right: 2px;
	border: 1px solid #c0c0c0;
}
.pagination_report span.current {
	padding: 4px 8px 4px 8px;
	margin-right: 2px;
	border: 1px solid #a0a0a0;
	font-weight: bold;
	background-color: #f0f0f0;
	color: #666;
}
.pagination_report span.disabled {
	padding: 4px 8px 4px 8px;
	margin-right: 2px;
	border: 1px solid #f3f3f3;
	color: #ccc;
}

.ui-dialog .ui-dialog-content {
  background: #f3f3f3 !important;
} 

.styled-select-type {
	background-color: #ffcc99;
	padding: 3px;
 	border: 1px solid #BBB;
    -moz-border-radius: 3px; 
    border-radius: 3px;
}
.styled-select {
	background-color: #E7EFEF;
	padding: 3px;
 	border: 1px solid #BBB;
    -moz-border-radius: 3px; 
    border-radius: 3px;
}
.styled-select-range {
	background-color: #F9F9F9;
 	border: 1px solid #BBB;
	padding: 2px;
	margin-top: 5px;
    -moz-border-radius: 3px; 
    border-radius: 3px;
}
.styled-input {
	margin-top: 4px;
	height: 17px;
	border: solid 1px #BBB;
	color: #F90;
	background-color: #F9F9F9;
    -moz-border-radius: 3px; 
    border-radius: 3px;	
}
.styled-input-range {
	margin-top: 4px;
	height: 17px;
	border: solid 1px #BBB;
	color: #F90;
    -moz-border-radius: 3px; 
    border-radius: 3px;	
}

a.multiSelect {
	background: #F9F9F9 url(view/image/adv_reports/dropdown.png) right center no-repeat;
	border: solid 1px #BBB;
	padding: 1px;
	padding-right: 19px;
	margin-top: 4px;	
	height: 18px;
	position: relative;
	cursor: default;
	text-decoration: none;
	color: black;
	display: -moz-inline-stack;
	display: inline-block;
	vertical-align: middle;
    -moz-border-radius: 3px; 
    border-radius: 3px;	
}
a.multiSelect:link, a.multiSelect:visited, a.multiSelect:hover, a.multiSelect:active {
	color: black;
	text-decoration: none;
	padding-top: 2px;	
}
a.multiSelect span {
	margin: 1px 0px 2px 4px;
	overflow: hidden;
	display: -moz-inline-stack;
	display: inline-block;
	min-width: 105px;
	max-width: 210px;	
}
.multiSelectOptions {
	margin-top: 2px;	
	overflow-y: auto;
	overflow-x: hidden;
	border: solid 1px #B2B2B2;
	background: #F9F9F9;
	padding-top: 2px;
	padding-bottom: 2px;
	min-width: 105px;
    -moz-border-radius: 3px; 
    border-radius: 3px;		
}
.multiSelectOptions LABEL {
	padding: 0px 2px 2px 2px;
	display: block;
	padding-top: 1px;
	padding-bottom: 1px;
	padding-left: 20px;	
}
.multiSelectOptions LABEL.optGroup {
	font-weight: bold;
}
.multiSelectOptions .optGroupContainer LABEL {
	padding-left: 10px;
}
.multiSelectOptions.optGroupHasCheckboxes .optGroupContainer LABEL {
	padding-left: 18px;
}
.multiSelectOptions input {
	vertical-align: middle;
	margin-left: -16px;
}
.multiSelectOptions LABEL.checked {
	background-color: #dce5f8;
}
.multiSelectOptions LABEL.selectAll {
	border-bottom: dotted 1px #CCC;
}
.multiSelectOptions LABEL.hover {
	background-color: #ffcc99;
	color: #000;
}
</style>
<form method="post" action="index.php?route=report/adv_sale_order&token=<?php echo $token; ?>" id="report" name="report">
<div id="content">
  <div class="box">
    <div class="heading order_head">
      <h1><div style="float:left;"><img src="view/image/adv_reports/adv_report_icon.png" width="22" height="22" alt="" /><?php echo $heading_title; ?></div></h1><span style="float:right; padding-top:5px; padding-right:5px; font-size:11px; color:#666; text-align:right;"><?php echo $heading_version; ?></span></div>
      <div align="right" style="height:38px; background-color:#F0F0F0; border: 1px solid #DDDDDD; margin-top:5px; white-space:nowrap;">
      <div style="padding-top: 5px; margin-right: 5px;"><?php echo $entry_report; ?>
          <select name="filter_report" id="filter_report" onchange="$('#report').submit();" class="styled-select-type"> 
              <?php foreach ($report as $report) { ?>
              <?php if ($report['value'] == $filter_report) { ?>
              <option value="<?php echo $report['value']; ?>" title="<?php echo $report['text']; ?>" selected="selected"><?php echo $report['text']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $report['value']; ?>" title="<?php echo $report['text']; ?>"><?php echo $report['text']; ?></option>
              <?php } ?>
              <?php } ?>
          </select>&nbsp;&nbsp;  
      	  <?php echo $entry_group; ?>
          <select name="filter_group" class="styled-select" <?php echo ($filter_details == 4 or $filter_report != 'sales_summary') ? 'disabled="disabled"' : '' ?>> 
			<?php foreach ($groups as $group) { ?>
			<?php if ($group['value'] == $filter_group) { ?>
			<option value="<?php echo $group['value']; ?>" selected="selected"><?php echo $group['text']; ?></option>
			<?php } else { ?>
			<option value="<?php echo $group['value']; ?>"><?php echo $group['text']; ?></option>
			<?php } ?>
			<?php } ?>
          	<?php if ($filter_details == 4 or $filter_report != 'sales_summary') { ?>
			<option selected="selected">----</option>
			<?php } ?>             
          </select>&nbsp;&nbsp; 
          <?php echo $entry_sort_by; ?>
		  <select name="filter_sort" class="styled-select" <?php echo ($filter_details == 4) ? 'disabled="disabled"' : '' ?>>
		  	<?php if ($filter_report == 'sales_summary') { ?>
                <?php if (!$filter_sort or $filter_sort == 'type') { ?>
            	<option value="type" selected="selected"><?php echo $column_date; ?></option>
            	<?php } else { ?>
            	<option value="type"><?php echo $column_date; ?></option>
                <?php } ?>           
		  	<?php } elseif ($filter_report == 'day_of_week') { ?>
                <?php if (!$filter_sort or $filter_sort == 'type') { ?>
            	<option value="type" selected="selected"><?php echo $column_day_of_week; ?></option>
            	<?php } else { ?>
            	<option value="type"><?php echo $column_day_of_week; ?></option>
                <?php } ?> 
		  	<?php } elseif ($filter_report == 'hour') { ?>
            	<?php if (!$filter_sort or $filter_sort == 'type') { ?>
            	<option value="type" selected="selected"><?php echo $column_hour; ?></option>
            	<?php } else { ?>
            	<option value="type"><?php echo $column_hour; ?></option>  
                <?php } ?> 
		  	<?php } elseif ($filter_report == 'store') { ?>
            	<?php if (!$filter_sort or $filter_sort == 'type') { ?>
            	<option value="type" selected="selected"><?php echo $column_store; ?></option>
            	<?php } else { ?>
            	<option value="type"><?php echo $column_store; ?></option>  
                <?php } ?>
		  	<?php } elseif ($filter_report == 'customer_group') { ?>
            	<?php if (!$filter_sort or $filter_sort == 'type') { ?>
            	<option value="type" selected="selected"><?php echo $column_customer_group; ?></option>
            	<?php } else { ?>
            	<option value="type"><?php echo $column_customer_group; ?></option>  
                <?php } ?>
		  	<?php } elseif ($filter_report == 'country') { ?>
            	<?php if (!$filter_sort or $filter_sort == 'type') { ?>
            	<option value="type" selected="selected"><?php echo $column_country; ?></option>
            	<?php } else { ?>
            	<option value="type"><?php echo $column_country; ?></option>  
                <?php } ?>
		  	<?php } elseif ($filter_report == 'postcode') { ?>
            	<?php if (!$filter_sort or $filter_sort == 'type') { ?>
            	<option value="type" selected="selected"><?php echo $column_postcode; ?></option>
            	<?php } else { ?>
            	<option value="type"><?php echo $column_postcode; ?></option>  
                <?php } ?>
		  	<?php } elseif ($filter_report == 'region_state') { ?>
            	<?php if (!$filter_sort or $filter_sort == 'type') { ?>
            	<option value="type" selected="selected"><?php echo $column_region_state; ?></option>
            	<?php } else { ?>
            	<option value="type"><?php echo $column_region_state; ?></option>  
                <?php } ?>
		  	<?php } elseif ($filter_report == 'city') { ?>
            	<?php if (!$filter_sort or $filter_sort == 'type') { ?>
            	<option value="type" selected="selected"><?php echo $column_city; ?></option>
            	<?php } else { ?>
            	<option value="type"><?php echo $column_city; ?></option>  
                <?php } ?>
		  	<?php } elseif ($filter_report == 'payment_method') { ?>
            	<?php if (!$filter_sort or $filter_sort == 'type') { ?>
            	<option value="type" selected="selected"><?php echo $column_payment_method; ?></option>
            	<?php } else { ?>
            	<option value="type"><?php echo $column_payment_method; ?></option>  
                <?php } ?> 
		  	<?php } elseif ($filter_report == 'shipping_method') { ?>
            	<?php if (!$filter_sort or $filter_sort == 'type') { ?>
            	<option value="type" selected="selected"><?php echo $column_shipping_method; ?></option>
            	<?php } else { ?>
            	<option value="type"><?php echo $column_shipping_method; ?></option>  
                <?php } ?>                                                                                                                                       
		  	<?php } ?>
            <?php if ($filter_sort == 'orders') { ?>
            <option value="orders" selected="selected"><?php echo $column_orders; ?></option>
            <?php } else { ?>
            <option value="orders"><?php echo $column_orders; ?></option>
            <?php } ?>            
            <?php if ($filter_sort == 'customers') { ?>
            <option value="customers" selected="selected"><?php echo $column_customers; ?></option>
            <?php } else { ?>
            <option value="customers"><?php echo $column_customers; ?></option>
            <?php } ?>
            <?php if ($filter_sort == 'products') { ?>
            <option value="products" selected="selected"><?php echo $column_products; ?></option>
            <?php } else { ?>
            <option value="products"><?php echo $column_products; ?></option>
            <?php } ?>
            <?php if ($filter_sort == 'sub_total') { ?>
            <option value="sub_total" selected="selected"><?php echo $column_sub_total; ?></option>
            <?php } else { ?>
            <option value="sub_total"><?php echo $column_sub_total; ?></option>
            <?php } ?> 
            <?php if ($filter_sort == 'shipping') { ?>
            <option value="shipping" selected="selected"><?php echo $column_shipping; ?></option>
            <?php } else { ?>
            <option value="shipping"><?php echo $column_shipping; ?></option>
            <?php } ?> 
            <?php if ($filter_sort == 'reward') { ?>
            <option value="reward" selected="selected"><?php echo $column_reward; ?></option>
            <?php } else { ?>
            <option value="reward"><?php echo $column_reward; ?></option>
            <?php } ?>             
            <?php if ($filter_sort == 'coupon') { ?>
            <option value="coupon" selected="selected"><?php echo $column_coupon; ?></option>
            <?php } else { ?>
            <option value="coupon"><?php echo $column_coupon; ?></option>
            <?php } ?> 
            <?php if ($filter_sort == 'tax') { ?>
            <option value="tax" selected="selected"><?php echo $column_tax; ?></option>
            <?php } else { ?>
            <option value="tax"><?php echo $column_tax; ?></option>
            <?php } ?> 
            <?php if ($filter_sort == 'credit') { ?>
            <option value="credit" selected="selected"><?php echo $column_credit; ?></option>
            <?php } else { ?>
            <option value="credit"><?php echo $column_credit; ?></option>
            <?php } ?>
            <?php if ($filter_sort == 'voucher') { ?>
            <option value="voucher" selected="selected"><?php echo $column_voucher; ?></option>
            <?php } else { ?>
            <option value="voucher"><?php echo $column_voucher; ?></option>
            <?php } ?>    
            <?php if ($filter_sort == 'commission') { ?>
            <option value="commission" selected="selected"><?php echo $column_commission; ?></option>
            <?php } else { ?>
            <option value="commission"><?php echo $column_commission; ?></option>
            <?php } ?>
            <?php if ($filter_sort == 'total') { ?>
            <option value="total" selected="selected"><?php echo $column_total; ?></option>
            <?php } else { ?>
            <option value="total"><?php echo $column_total; ?></option>
            <?php } ?>   
          	<?php if ($filter_details == 4) { ?>
			<option selected="selected">----</option>
			<?php } ?>             
          </select>&nbsp;&nbsp; 
          <?php echo $entry_show_details; ?>
		  <select name="filter_details" class="styled-select">                      
            <?php if (!$filter_details or $filter_details == '0') { ?>
            <option value="0" selected="selected"><?php echo $text_no_details; ?></option>
            <?php } else { ?>
            <option value="0"><?php echo $text_no_details; ?></option>
            <?php } ?>
            <?php if ($filter_details == '1') { ?>
            <option value="1" selected="selected"><?php echo $text_order_list; ?></option>
            <?php } else { ?>
            <option value="1"><?php echo $text_order_list; ?></option>
            <?php } ?>
            <?php if ($filter_details == '2') { ?>
            <option value="2" selected="selected"><?php echo $text_product_list; ?></option>
            <?php } else { ?>
            <option value="2"><?php echo $text_product_list; ?></option>
            <?php } ?>
            <?php if ($filter_details == '3') { ?>
            <option value="3" selected="selected"><?php echo $text_customer_list; ?></option>
            <?php } else { ?>
            <option value="3"><?php echo $text_customer_list; ?></option>
            <?php } ?>
            <?php if ($filter_details == '4') { ?>
            <option value="4" selected="selected"><?php echo $text_all_details; ?></option>
            <?php } else { ?>
            <option value="4"><?php echo $text_all_details; ?></option>
            <?php } ?>
          </select>&nbsp;&nbsp; 
          <?php echo $entry_limit; ?>
		  <select name="filter_limit" class="styled-select"> 
            <?php if ($filter_limit == '10') { ?>
            <option value="10" selected="selected">10</option>
            <?php } else { ?>
            <option value="10">10</option>
            <?php } ?>                                
            <?php if (!$filter_limit or $filter_limit == '25') { ?>
            <option value="25" selected="selected">25</option>
            <?php } else { ?>
            <option value="25">25</option>
            <?php } ?>
            <?php if ($filter_limit == '50') { ?>
            <option value="50" selected="selected">50</option>
            <?php } else { ?>
            <option value="50">50</option>
            <?php } ?>
            <?php if ($filter_limit == '100') { ?>
            <option value="100" selected="selected">100</option>
            <?php } else { ?>
            <option value="100">100</option>
            <?php } ?>                        
          </select>&nbsp; <a onclick="$('#report').submit();" class="cbutton" style="background:#069;"><span><?php echo $button_filter; ?></span></a>&nbsp;<?php if ($orders) { ?><?php if ((($filter_report == 'day_of_week' or $filter_report == 'hour' or $filter_report == 'store' or $filter_report == 'customer_group' or $filter_report == 'country' or $filter_report == 'payment_method' or $filter_report == 'shipping_method') && $filter_details != '4') or ($filter_report == 'sales_summary' && $filter_details != '4' && ($filter_group == 'year' or $filter_group == 'quarter' or $filter_group == 'month'))) { ?><a id="show_tab_chart" class="cbutton" style="background:#993333;"><span><?php echo $button_chart; ?></span></a><?php } ?><?php } ?>&nbsp;<a id="show_tab_export" class="cbutton" style="background:#699;"><span><?php echo $button_export; ?></span></a>&nbsp;<a id="settings" class="cbutton" style="background:#666;"><span><?php echo $button_settings; ?></span></a>&nbsp;<a href="http://www.opencartreports.com/documentation/so/index.html" target="_blank" class="cbutton" style="background:#cc6633;"><span><?php echo $button_documentation; ?></span></a></div>
    </div>
    <div class="content_report">
<script type="text/javascript"><!--
$(document).ready(function() {
var prev = {start: 0, stop: 0},
    cont = $('#pagination_content #element');
	
$('.pagination_report').paging(cont.length, {
	format: '[< ncnnn! >]',
	perpage: '<?php echo $filter_limit; ?>',	
	lapping: 0,
	page: null, // we await hashchange() event
			onSelect: function() {

				var data = this.slice;

				cont.slice(prev[0], prev[1]).css('display', 'none');
				cont.slice(data[0], data[1]).fadeIn(0);

				prev = data;

				return true; // locate!
			},
			onFormat: function (type) {

				switch (type) {

					case 'block':

						if (!this.active)
							return '<span class="disabled">' + this.value + '</span>';
						else if (this.value != this.page)
							return '<em><a href="index.php?route=report/adv_sale_order&token=<?php echo $token; ?>#' + this.value + '">' + this.value + '</a></em>';
						return '<span class="current">' + this.value + '</span>';

					case 'next':

						if (this.active) {
							return '<a href="index.php?route=report/adv_sale_order&token=<?php echo $token; ?>#' + this.value + '" class="next">Next &gt;</a>';
						}
						return '';						

					case 'prev':

						if (this.active) {
							return '<a href="index.php?route=report/adv_sale_order&token=<?php echo $token; ?>#' + this.value + '" class="prev">&lt; Previous</a>';
						}	
						return '';						

					case 'first':

						if (this.active) {
							return '<?php echo $text_pagin_page; ?> ' + this.page + ' <?php echo $text_pagin_of; ?> ' + this.pages + '&nbsp;&nbsp;<a href="index.php?route=report/adv_sale_order&token=<?php echo $token; ?>#' + this.value + '" class="first">|&lt;</a>';
						}	
						return '<?php echo $text_pagin_page; ?> ' + this.page + ' <?php echo $text_pagin_of; ?> ' + this.pages + '&nbsp;&nbsp';
							
					case 'last':

						if (this.active) {
							return '<a href="index.php?route=report/adv_sale_order&token=<?php echo $token; ?>#' + this.value + '" class="prev">&gt;|</a>&nbsp;&nbsp;(' + cont.length + ' <?php echo $text_pagin_results; ?>)';
						}
						return '&nbsp;&nbsp;(' + cont.length + ' <?php echo $text_pagin_results; ?>)';					

				}
				return ''; // return nothing for missing branches
			}
});
});		
//--></script>         
<script type="text/javascript"><!--
function getStorage(key_prefix) {
    // this function will return us an object with a "set" and "get" method
    if (window.localStorage) {
        // use localStorage:
        return {
            set: function(id, data) {
                localStorage.setItem(key_prefix+id, data);
            },
            get: function(id) {
                return localStorage.getItem(key_prefix+id);
            }
        };
    }
}

$(document).ready(function() {
    // a key must is used for the cookie/storage
    var storedData = getStorage('com_mysite_checkboxes_'); 
    
    $('div.check input:checkbox').bind('change',function(){
        $('#'+this.id+'_filter').toggle($(this).is(':checked'));
        $('#'+this.id+'_title').toggle($(this).is(':checked'));
			<?php if ($orders) {
					foreach ($orders as $key => $order) {
						echo "$('#'+this.id+'_" . $order['order_id'] . "_title').toggle($(this).is(':checked')); ";
						echo "$('#'+this.id+'_" . $order['order_id'] . "').toggle($(this).is(':checked')); ";						
					}			
			} 
			;?>		
        $('#'+this.id+'_total').toggle($(this).is(':checked'));			
        // save the data on change
        storedData.set(this.id, $(this).is(':checked')?'checked':'not');
    }).each(function() {
        // on load, set the value to what we read from storage:
        var val = storedData.get(this.id);
        if (val == 'checked') $(this).attr('checked', 'checked');
        if (val == 'not') $(this).removeAttr('checked');
        if (val) $(this).trigger('change');
    });
});
//--></script>
<div id="settings_window" style="display:none">
<div class="check">
<table align="center" cellspacing="0" cellpadding="0">   
    <tr><td>
      &nbsp;<span style="font-size:14px; font-weight:bold;"><?php echo $text_filtering_options; ?></span><br />        
      <table width="100%" cellspacing="0" cellpadding="3" style="background:#E7EFEF; border:1px solid #DDDDDD; margin-top:3px;">
        <tr>
          <td>
			<div class="columns_setting"><input id="so2" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so2"><?php echo substr($entry_store,0,-1); ?></label></div>
			<div class="columns_setting"><input id="so3" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so3"><?php echo substr($entry_currency,0,-1); ?></label></div>
			<div class="columns_setting"><input id="so4a" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so4a"><?php echo substr($entry_tax,0,-1); ?></label></div>
			<div class="columns_setting"><input id="so4c" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so4c"><?php echo substr($entry_tax_classes,0,-1); ?></label></div>
			<div class="columns_setting"><input id="so4b" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so4b"><?php echo substr($entry_geo_zone,0,-1); ?></label></div>
			<div class="columns_setting"><input id="so5" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so5"><?php echo substr($entry_customer_group,0,-1); ?></label></div>
			<div class="columns_setting"><input id="so7" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so7"><?php echo substr($entry_customer_name,0,-1); ?></label></div>
			<div class="columns_setting"><input id="so8a" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so8a"><?php echo substr($entry_customer_email,0,-1); ?></label></div>
			<div class="columns_setting"><input id="so8b" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so8b"><?php echo substr($entry_customer_telephone,0,-1); ?></label></div>
            <div class="columns_setting"><input id="so8c" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so8c"><?php echo substr($entry_ip,0,-1); ?></label></div>
			<div class="columns_setting"><input id="so17a" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so17a"><?php echo substr($entry_payment_company,0,-1); ?></label></div>
			<div class="columns_setting"><input id="so17b" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so17b"><?php echo substr($entry_payment_address,0,-1); ?></label></div>
			<div class="columns_setting"><input id="so17c" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so17c"><?php echo substr($entry_payment_city,0,-1); ?></label></div>
			<div class="columns_setting"><input id="so17d" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so17d"><?php echo substr($entry_payment_zone,0,-1); ?></label></div>
			<div class="columns_setting"><input id="so17e" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so17e"><?php echo substr($entry_payment_postcode,0,-1); ?></label></div>
            <div class="columns_setting"><input id="so17f" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so17f"><?php echo substr($entry_payment_country,0,-1); ?></label></div>
            <div class="columns_setting"><input id="so13" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so13"><?php echo substr($entry_payment_method,0,-1); ?></label></div>
			<div class="columns_setting"><input id="so16a" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so16a"><?php echo substr($entry_shipping_company,0,-1); ?></label></div>
			<div class="columns_setting"><input id="so16b" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so16b"><?php echo substr($entry_shipping_address,0,-1); ?></label></div>
			<div class="columns_setting"><input id="so16c" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so16c"><?php echo substr($entry_shipping_city,0,-1); ?></label></div>
			<div class="columns_setting"><input id="so16d" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so16d"><?php echo substr($entry_shipping_zone,0,-1); ?></label></div>
			<div class="columns_setting"><input id="so16e" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so16e"><?php echo substr($entry_shipping_postcode,0,-1); ?></label></div>
			<div class="columns_setting"><input id="so16f" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so16f"><?php echo substr($entry_shipping_country,0,-1); ?></label></div>
            <div class="columns_setting"><input id="so14" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so14"><?php echo substr($entry_shipping_method,0,-1); ?></label></div>
			<div class="columns_setting"><input id="so9d" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so9d"><?php echo substr($entry_category,0,-1); ?></label></div>
            <div class="columns_setting"><input id="so9e" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so9e"><?php echo substr($entry_manufacturer,0,-1); ?></label></div>
            <div class="columns_setting"><input id="so9a" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so9a"><?php echo substr($entry_sku,0,-1); ?></label></div>
            <div class="columns_setting"><input id="so9b" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so9b"><?php echo substr($entry_product,0,-1); ?></label></div>
            <div class="columns_setting"><input id="so9c" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so9c"><?php echo substr($entry_model,0,-1); ?></label></div>
            <div class="columns_setting"><input id="so10" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so10"><?php echo substr($entry_option,0,-1); ?></label></div>
            <div class="columns_setting"><input id="so18" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so18"><?php echo substr($entry_attributes,0,-1); ?></label></div>
            <div class="columns_setting"><input id="so11" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so11"><?php echo substr($entry_location,0,-1); ?></label></div>
            <div class="columns_setting"><input id="so12a" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so12a"><?php echo substr($entry_affiliate_name,0,-1); ?></label></div>
            <div class="columns_setting"><input id="so12b" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so12b"><?php echo substr($entry_affiliate_email,0,-1); ?></label></div>
            <div class="columns_setting"><input id="so15a" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so15a"><?php echo substr($entry_coupon_name,0,-1); ?></label></div>
            <div class="columns_setting"><input id="so15b" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so15b"><?php echo substr($entry_coupon_code,0,-1); ?></label></div>
            <div class="columns_setting"><input id="so19" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so19"><?php echo substr($entry_voucher_code,0,-1); ?></label></div>
          </td>                                                                                                                        
        </tr>        
      </table><br />         
      &nbsp;<span style="font-size:14px; font-weight:bold;"><?php echo $text_column_settings; ?></span><br />  
      <table width="100%" cellspacing="0" cellpadding="3" style="background:#E5E5E5; border:1px solid #DDDDDD; margin-top:3px;">
        <tr>
          <td>
            &nbsp;<span style="font-size:11px; font-weight:bold;"><?php echo $text_mv_columns; ?></span><br />           
			<div class="columns_setting"><input id="so20" name="so20" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so20"><?php echo $column_orders; ?></label></div>
			<div class="columns_setting"><input id="so21" name="so21" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so21"><?php echo $column_customers; ?></label></div>
			<div class="columns_setting"><input id="so22" name="so22" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so22"><?php echo $column_products; ?></label></div>
			<div class="columns_setting"><input id="so23" name="so23" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so23"><?php echo $column_sub_total; ?></label></div>
			<div class="columns_setting"><input id="so24" name="so24" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so24"><?php echo $column_handling; ?></label></div>
			<div class="columns_setting"><input id="so25" name="so25" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so25"><?php echo $column_loworder; ?></label></div>
			<div class="columns_setting"><input id="so27" name="so27" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so27"><?php echo $column_shipping; ?></label></div>
			<div class="columns_setting"><input id="so26" name="so26" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so26"><?php echo $column_reward; ?></label></div>
            <div class="columns_setting"><input id="so28" name="so28" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so28"><?php echo $column_coupon; ?></label></div>
            <div class="columns_setting"><input id="so29" name="so29" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so29"><?php echo $column_tax; ?></label></div>
            <div class="columns_setting"><input id="so30" name="so30" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so30"><?php echo $column_credit; ?></label></div>
            <div class="columns_setting"><input id="so31" name="so31" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so31"><?php echo $column_voucher; ?></label></div>
            <div class="columns_setting"><input id="so32" name="so32" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so32"><?php echo $column_commission; ?></label></div>
            <div class="columns_setting"><input id="so33" name="so33" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so33"><?php echo $column_total; ?></label></div>
          </td>                                                                                                                        
        </tr>
		<tr><td>
		<span style="font-size:11px; color:#390;">* <?php echo $text_export_note; ?></span>  
		</td></tr>          
      </table>
      <table width="100%" cellspacing="0" cellpadding="3" style="background:#F0F0F0; border:1px solid #DDDDDD; margin-top:3px;">
        <tr>
          <td>
            &nbsp;<span style="font-size:11px; font-weight:bold;"><?php echo $text_ol_columns; ?></span><br />            
			<div class="columns_setting"><input id="so40" name="so40" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so40"><?php echo $column_order_order_id; ?></label></div>
			<div class="columns_setting"><input id="so41" name="so41" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so41"><?php echo $column_order_date_added; ?></label></div>
			<div class="columns_setting"><input id="so42" name="so42" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so42"><?php echo $column_order_inv_no; ?></label></div>
			<div class="columns_setting"><input id="so43" name="so43" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so43"><?php echo $column_order_customer; ?></label></div>
			<div class="columns_setting"><input id="so44" name="so44" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so44"><?php echo $column_order_email; ?></label></div>
			<div class="columns_setting"><input id="so45" name="so45" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so45"><?php echo $column_order_customer_group; ?></label></div>
			<div class="columns_setting"><input id="so46" name="so46" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so46"><?php echo $column_order_shipping_method; ?></label></div>	
            <div class="columns_setting"><input id="so47" name="so47" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so47"><?php echo $column_order_payment_method; ?></label></div>
            <div class="columns_setting"><input id="so48" name="so48" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so48"><?php echo $column_order_status; ?></label></div>
            <div class="columns_setting"><input id="so49" name="so49" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so49"><?php echo $column_order_store; ?></label></div>
            <div class="columns_setting"><input id="so50" name="so50" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so50"><?php echo $column_order_currency; ?></label></div>
            <div class="columns_setting"><input id="so51" name="so51" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so51"><?php echo $column_order_quantity; ?></label></div>
            <div class="columns_setting"><input id="so52" name="so52" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so52"><?php echo $column_order_sub_total; ?></label></div>
            <div class="columns_setting"><input id="so54" name="so54" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so54"><?php echo $column_order_shipping; ?></label></div>
            <div class="columns_setting"><input id="so55" name="so55" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so55"><?php echo $column_order_tax; ?></label></div>
            <div class="columns_setting"><input id="so56" name="so56" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so56"><?php echo $column_order_value; ?></label></div>
          </td>                                                                                                                        
        </tr>
		<tr><td>
		<span style="font-size:11px; color:#390;">* <?php echo $text_export_note; ?> - <strong><?php echo strip_tags($text_export_order_list); ?></strong></span>  
		</td></tr>          
      </table>
      <table width="100%" cellspacing="0" cellpadding="3" style="background:#F0F0F0; border:1px solid #DDDDDD; margin-top:3px;">
        <tr>
          <td>
            &nbsp;<span style="font-size:11px; font-weight:bold;"><?php echo $text_pl_columns; ?></span><br />              
			<div class="columns_setting"><input id="so60" name="so60" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so60"><?php echo $column_prod_order_id; ?></label></div>
			<div class="columns_setting"><input id="so61" name="so61" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so61"><?php echo $column_prod_date_added; ?></label></div>
			<div class="columns_setting"><input id="so62" name="so62" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so62"><?php echo $column_prod_inv_no; ?></label></div>
			<div class="columns_setting"><input id="so63" name="so63" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so63"><?php echo $column_prod_id; ?></label></div>
			<div class="columns_setting"><input id="so64" name="so64" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so64"><?php echo $column_prod_sku; ?></label></div>
			<div class="columns_setting"><input id="so65" name="so65" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so65"><?php echo $column_prod_model; ?></label></div>
			<div class="columns_setting"><input id="so66" name="so66" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so66"><?php echo $column_prod_name; ?></label></div>
			<div class="columns_setting"><input id="so67" name="so67" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so67"><?php echo $column_prod_option; ?></label></div>
			<div class="columns_setting"><input id="so77" name="so77" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so77"><?php echo $column_prod_attributes; ?></label></div>
            <div class="columns_setting"><input id="so68" name="so68" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so68"><?php echo $column_prod_manu; ?></label></div> 
            <div class="columns_setting"><input id="so79" name="so79" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so79"><?php echo $column_prod_category; ?></label></div>
            <div class="columns_setting"><input id="so69" name="so69" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so69"><?php echo $column_prod_currency; ?></label></div>
            <div class="columns_setting"><input id="so70" name="so70" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so70"><?php echo $column_prod_price; ?></label></div>
            <div class="columns_setting"><input id="so71" name="so71" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so71"><?php echo $column_prod_quantity; ?></label></div>
            <div class="columns_setting"><input id="so72a" name="so72a" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so72a"><?php echo $column_prod_total_excl_vat; ?></label></div>
            <div class="columns_setting"><input id="so73" name="so73" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so73"><?php echo $column_prod_tax; ?></label></div>
            <div class="columns_setting"><input id="so72b" name="so72b" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so72b"><?php echo $column_prod_total_incl_vat; ?></label></div>
          </td>                                                                                                                        
        </tr>
		<tr><td>
		<span style="font-size:11px; color:#390;">* <?php echo $text_export_note; ?> - <strong><?php echo strip_tags($text_export_product_list); ?></strong></span>  
		</td></tr>          
      </table>
      <table width="100%" cellspacing="0" cellpadding="3" style="background:#F0F0F0; border:1px solid #DDDDDD; margin-top:3px;">
        <tr>
          <td>
            &nbsp;<span style="font-size:11px; font-weight:bold;"><?php echo $text_cl_columns; ?></span><br />             
			<div class="columns_setting"><input id="so80" name="so80" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so80"><?php echo $column_customer_order_id; ?></label></div>
			<div class="columns_setting"><input id="so81" name="so81" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so81"><?php echo $column_customer_date_added; ?></label></div>
			<div class="columns_setting"><input id="so82" name="so82" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so82"><?php echo $column_customer_inv_no; ?></label></div>
			<div class="columns_setting"><input id="so83" name="so83" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so83"><?php echo $column_customer_cust_id; ?></label></div>
			<div class="columns_setting"><input id="so84" name="so84" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so84"><?php echo strip_tags($column_billing_name); ?></label></div>
			<div class="columns_setting"><input id="so85" name="so85" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so85"><?php echo strip_tags($column_billing_company); ?></label></div>
			<div class="columns_setting"><input id="so86" name="so86" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so86"><?php echo strip_tags($column_billing_address_1); ?></label></div>
			<div class="columns_setting"><input id="so87" name="so87" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so87"><?php echo strip_tags($column_billing_address_2); ?></label></div>			
            <div class="columns_setting"><input id="so88" name="so88" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so88"><?php echo strip_tags($column_billing_city); ?></label></div>
            <div class="columns_setting"><input id="so89" name="so89" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so89"><?php echo strip_tags($column_billing_zone); ?></label></div>
            <div class="columns_setting"><input id="so90" name="so90" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so90"><?php echo strip_tags($column_billing_postcode); ?></label></div>
            <div class="columns_setting"><input id="so91" name="so91" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so91"><?php echo strip_tags($column_billing_country); ?></label></div>
            <div class="columns_setting"><input id="so92" name="so92" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so92"><?php echo $column_customer_telephone; ?></label></div>
			<div class="columns_setting"><input id="so93" name="so93" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so93"><?php echo strip_tags($column_shipping_name); ?></label></div>
			<div class="columns_setting"><input id="so94" name="so94" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so94"><?php echo strip_tags($column_shipping_company); ?></label></div>
			<div class="columns_setting"><input id="so95" name="so95" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so95"><?php echo strip_tags($column_shipping_address_1); ?></label></div>
			<div class="columns_setting"><input id="so96" name="so96" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so96"><?php echo strip_tags($column_shipping_address_2); ?></label></div>
            <div class="columns_setting"><input id="so97" name="so97" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so97"><?php echo strip_tags($column_shipping_city); ?></label></div>
            <div class="columns_setting"><input id="so98" name="so98" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so98"><?php echo strip_tags($column_shipping_zone); ?></label></div>
            <div class="columns_setting"><input id="so99" name="so99" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so99"><?php echo strip_tags($column_shipping_postcode); ?></label></div>
            <div class="columns_setting"><input id="so100" name="so100" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so100"><?php echo strip_tags($column_shipping_country); ?></label></div>
          </td>                                                                                                                        
        </tr>
		<tr><td>
		<span style="font-size:11px; color:#390;">* <?php echo $text_export_note; ?> - <strong><?php echo strip_tags($text_export_customer_list); ?></strong></span>  
		</td></tr>         
      </table>
      <table width="100%" cellspacing="0" cellpadding="3" style="background:#F0F0F0; border:1px solid #DDDDDD; margin-top:3px;">
        <tr>
          <td>
            &nbsp;<span style="font-size:11px; font-weight:bold;"><?php echo $text_all_columns; ?></span><br />
			<div class="columns_setting"><input id="so1000" name="so1000" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so1000"><?php echo $column_order_inv_no; ?></label></div>
			<div class="columns_setting"><input id="so1001" name="so1001" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so1001"><?php echo $column_order_customer_name; ?></label></div>
			<div class="columns_setting"><input id="so1002" name="so1002" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so1002"><?php echo $column_order_email; ?></label></div>
			<div class="columns_setting"><input id="so1003" name="so1003" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so1003"><?php echo $column_order_customer_group; ?></label></div>
			<div class="columns_setting"><input id="so1004" name="so1004" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so1004"><?php echo $column_prod_id; ?></label></div>
			<div class="columns_setting"><input id="so1005" name="so1005" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so1005"><?php echo $column_prod_sku; ?></label></div>
			<div class="columns_setting"><input id="so1006" name="so1006" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so1006"><?php echo $column_prod_model; ?></label></div>
			<div class="columns_setting"><input id="so1007" name="so1007" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so1007"><?php echo $column_prod_name; ?></label></div>
			<div class="columns_setting"><input id="so1008" name="so1008" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so1008"><?php echo $column_prod_option; ?></label></div>
			<div class="columns_setting"><input id="so1009" name="so1009" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so1009"><?php echo $column_prod_attributes; ?></label></div>
			<div class="columns_setting"><input id="so1010" name="so1010" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so1010"><?php echo $column_prod_manu; ?></label></div>
			<div class="columns_setting"><input id="so1011" name="so1011" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so1011"><?php echo $column_prod_category; ?></label></div>            
			<div class="columns_setting"><input id="so1012" name="so1012" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so1012"><?php echo $column_prod_currency; ?></label></div>
			<div class="columns_setting"><input id="so1062" name="so1062" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so1062"><?php echo $column_order_quantity; ?></label></div>
			<div class="columns_setting"><input id="so1013" name="so1013" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so1013"><?php echo $column_prod_price; ?></label></div>
			<div class="columns_setting"><input id="so1014" name="so1014" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so1014"><?php echo $column_prod_quantity; ?></label></div>
            <div class="columns_setting"><input id="so1016a" name="so1016a" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so1016a"><?php echo $column_prod_total_excl_vat; ?></label></div>
            <div class="columns_setting"><input id="so1015" name="so1015" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so1015"><?php echo $column_prod_tax; ?></label></div>            
            <div class="columns_setting"><input id="so1016b" name="so1016b" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so1016b"><?php echo $column_prod_total_incl_vat; ?></label></div>
            <div class="columns_setting"><input id="so1020" name="so1020" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so1020"><?php echo $column_sub_total; ?></label></div>
            <div class="columns_setting"><input id="so1021" name="so1021" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so1021"><?php echo $column_handling; ?></label></div>
            <div class="columns_setting"><input id="so1022" name="so1022" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so1022"><?php echo $column_loworder; ?></label></div>
            <div class="columns_setting"><input id="so1023" name="so1023" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so1023"><?php echo $column_shipping; ?></label></div>
            <div class="columns_setting"><input id="so1024" name="so1024" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so1024"><?php echo $column_reward; ?></label></div>
            <div class="columns_setting"><input id="so1025" name="so1025" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so1025"><?php echo $column_coupon; ?></label></div>
            <div class="columns_setting"><input id="so1026" name="so1026" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so1026"><?php echo $column_coupon_code; ?></label></div>
            <div class="columns_setting"><input id="so1027" name="so1027" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so1027"><?php echo $column_order_tax; ?></label></div>
            <div class="columns_setting"><input id="so1028" name="so1028" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so1028"><?php echo $column_credit; ?></label></div>
            <div class="columns_setting"><input id="so1029" name="so1029" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so1029"><?php echo $column_voucher; ?></label></div>
            <div class="columns_setting"><input id="so1030" name="so1030" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so1030"><?php echo $column_voucher_code; ?></label></div>
            <div class="columns_setting"><input id="so1034" name="so1034" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so1034"><?php echo $column_commission; ?></label></div>
            <div class="columns_setting"><input id="so1031" name="so1031" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so1031"><?php echo $column_order_value; ?></label></div>
			<div class="columns_setting"><input id="so1040" name="so1040" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so1040"><?php echo $column_order_shipping_method; ?></label></div>
			<div class="columns_setting"><input id="so1041" name="so1041" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so1041"><?php echo $column_order_payment_method; ?></label></div>
			<div class="columns_setting"><input id="so1042" name="so1042" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so1042"><?php echo $column_order_status; ?></label></div>
			<div class="columns_setting"><input id="so1043" name="so1043" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so1043"><?php echo $column_order_store; ?></label></div>
			<div class="columns_setting"><input id="so1044" name="so1044" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so1044"><?php echo $column_customer_cust_id; ?></label></div>
			<div class="columns_setting"><input id="so1045" name="so1045" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so1045"><?php echo strip_tags($column_billing_name); ?></label></div>
			<div class="columns_setting"><input id="so1046" name="so1046" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so1046"><?php echo strip_tags($column_billing_company); ?></label></div>
			<div class="columns_setting"><input id="so1047" name="so1047" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so1047"><?php echo strip_tags($column_billing_address_1); ?></label></div>
			<div class="columns_setting"><input id="so1048" name="so1048" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so1048"><?php echo strip_tags($column_billing_address_2); ?></label></div>
            <div class="columns_setting"><input id="so1049" name="so1049" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so1049"><?php echo strip_tags($column_billing_city); ?></label></div>
            <div class="columns_setting"><input id="so1050" name="so1050" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so1050"><?php echo strip_tags($column_billing_zone); ?></label></div>
            <div class="columns_setting"><input id="so1051" name="so1051" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so1051"><?php echo strip_tags($column_billing_postcode); ?></label></div>
            <div class="columns_setting"><input id="so1052" name="so1052" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so1052"><?php echo strip_tags($column_billing_country); ?></label></div>
            <div class="columns_setting"><input id="so1053" name="so1053" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so1053"><?php echo $column_customer_telephone; ?></label></div>
			<div class="columns_setting"><input id="so1054" name="so1054" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so1054"><?php echo strip_tags($column_shipping_name); ?></label></div>
			<div class="columns_setting"><input id="so1055" name="so1055" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so1055"><?php echo strip_tags($column_shipping_company); ?></label></div>
			<div class="columns_setting"><input id="so1056" name="so1056" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so1056"><?php echo strip_tags($column_shipping_address_1); ?></label></div>
			<div class="columns_setting"><input id="so1057" name="so1057" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so1057"><?php echo strip_tags($column_shipping_address_2); ?></label></div>
            <div class="columns_setting"><input id="so1058" name="so1058" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so1058"><?php echo strip_tags($column_shipping_city); ?></label></div>
            <div class="columns_setting"><input id="so1059" name="so1059" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so1059"><?php echo strip_tags($column_shipping_zone); ?></label></div>
            <div class="columns_setting"><input id="so1060" name="so1060" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so1060"><?php echo strip_tags($column_shipping_postcode); ?></label></div>
            <div class="columns_setting"><input id="so1061" name="so1061" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so1061"><?php echo strip_tags($column_shipping_country); ?></label></div>
            <div class="columns_setting"><input id="so1064" name="so1064" checked="checked" type="checkbox" style="vertical-align: middle;"><label for="so1064"><?php echo $column_order_comment; ?></label></div>
          </td>                                                                                                                        
        </tr>
		<tr><td>
		<span style="font-size:11px; color:#390;">* <?php echo $text_export_note; ?> - <strong><?php echo strip_tags($text_export_all_details); ?></strong></span>  
		</td></tr>         
      </table>     
	</td></tr>              
</table>     
</div>
</div>  
<script type="text/javascript"><!--
$("input[type='checkbox']").change(function() {
    if ($(this).is(":checked")) {
        $(this).parent().removeClass("set_unchecked"); 
    } else {
        $(this).parent().addClass("set_unchecked");  
    }
});
//--></script>  
<script type="text/javascript">
$("#settings").click(function() {					  
    var dlg = $("#settings_window").dialog({
            title: '<?php echo $button_settings; ?>',
            width: 900,
            height: 600,
            modal: true,			
    });
	dlg.parent().appendTo($("#report"));
});
</script> 
<script type="text/javascript">$(function(){ 
$('#show_tab_export').click(function() {
		$('#tab_export').slideToggle('fast');
	});
});
</script> 
  <div id="tab_export" style="background:#E7EFEF; border:1px solid #C6D7D7; padding:3px; margin-bottom:10px; -moz-border-radius: 3px; border-radius: 3px; display:none">
      <table width="100%" cellspacing="0" cellpadding="3">
        <tr>
          <td width="6%">&nbsp;</td>
          <td width="23%" align="center" nowrap="nowrap">
          	<span id="export_xls" class="export_item"><img src="view/image/adv_reports/XLS.png" width="48" height="48" border="0" title="XLS (Excel 97/2000)" /></span>
            <span id="export_xlsx" class="export_item"><img src="view/image/adv_reports/XLSX.png" width="48" height="48" border="0" title="XLSX (Excel 2007/2010)" /></span>
            <span id="export_csv" class="export_item"><img src="view/image/adv_reports/CSV.png" width="48" height="48" border="0" title="CSV" /></span>
            <span id="export_html" class="export_item"><img src="view/image/adv_reports/HTML.png" width="48" height="48" border="0" title="HTML" /></span>
            <span id="export_pdf" class="export_item"><img src="view/image/adv_reports/PDF.png" width="48" height="48" border="0" title="PDF" /></span></td>
          <td width="23%" align="center" nowrap="nowrap">
          	<span id="export_xls_all_details" class="export_item"><img src="view/image/adv_reports/XLS.png" width="48" height="48" border="0" title="XLS (Excel 97/2000)" /></span>
            <span id="export_xlsx_all_details" class="export_item"><img src="view/image/adv_reports/XLSX.png" width="48" height="48" border="0" title="XLSX (Excel 2007/2010)" /></span>
          	<span id="export_csv_all_details" class="export_item"><img src="view/image/adv_reports/CSV.png" width="48" height="48" border="0" title="CSV" /></span>
          	<span id="export_html_all_details" class="export_item"><img src="view/image/adv_reports/HTML.png" width="48" height="48" border="0" title="HTML" /></span>
          	<span id="export_pdf_all_details" class="export_item"><img src="view/image/adv_reports/PDF.png" width="48" height="48" border="0" title="PDF" /></span></td>
          <td width="14%" align="center" nowrap="nowrap">
            <span id="export_html_order_list" class="export_item"><img src="view/image/adv_reports/HTML.png" width="48" height="48" border="0" title="HTML" /></span>
            <span id="export_pdf_order_list" class="export_item"><img src="view/image/adv_reports/PDF.png" width="48" height="48" border="0" title="PDF" /></span></td>
          <td width="14%" align="center" nowrap="nowrap">
            <span id="export_html_product_list" class="export_item"><img src="view/image/adv_reports/HTML.png" width="48" height="48" border="0" title="HTML" /></span>
            <span id="export_pdf_product_list" class="export_item"><img src="view/image/adv_reports/PDF.png" width="48" height="48" border="0" title="PDF" /></span></td>
          <td width="14%" align="center" nowrap="nowrap">
          	<span id="export_html_customer_list" class="export_item"><img src="view/image/adv_reports/HTML.png" width="48" height="48" border="0" title="HTML" /></span>
          	<span id="export_pdf_customer_list" class="export_item"><img src="view/image/adv_reports/PDF.png" width="48" height="48" border="0" title="PDF" /></span></td>
          <td width="6%">&nbsp;</td>
        </tr>
        <tr>
          <td width="6%">&nbsp;</td>
          <td width="23%" align="center" nowrap="nowrap"><?php echo $text_export_no_details; ?></td>
          <td width="23%" align="center" nowrap="nowrap"><?php echo $text_export_all_details; ?></td>          
          <td width="14%" align="center" nowrap="nowrap"><?php echo $text_export_order_list; ?></td>
          <td width="14%" align="center" nowrap="nowrap"><?php echo $text_export_product_list; ?></td>
          <td width="14%" align="center" nowrap="nowrap"><?php echo $text_export_customer_list; ?></td>
          <td width="6%">&nbsp;</td>
        </tr>     
        <tr>
          <td colspan="7">*<span style="font-size:10px"><?php echo $text_export_notice1; ?> <a href="http://www.opencartreports.com/documentation/so/index.html#req_limit" target="_blank"><strong><?php echo $text_export_limit; ?></strong></a> <?php echo $text_export_notice2; ?></span></td>
        </tr>        
      </table>
  <input type="hidden" id="export" name="export" value="" />   
  </div> 
<script type="text/javascript">
$(document).ready(function() {
var $filter_range = $('#filter_range'), $date_start = $('#date-start'), $date_end = $('#date-end');
$filter_range.change(function () {
    if ($filter_range.val() == 'custom') {
        $date_start.removeAttr('disabled');
        $date_end.removeAttr('disabled');
    } else {	
        $date_start.attr('disabled', 'disabled').val('');
        $date_end.attr('disabled', 'disabled').val('');
    }
}).trigger('change');
});
</script>  
<div style="background: #E7EFEF; border: 1px solid #C6D7D7; margin-bottom: 15px; -moz-border-radius: 3px; border-radius: 3px;">
	<table width="100%" cellspacing="0" cellpadding="3">
	<tr>
	<td>
	 <table cellspacing="0" cellpadding="0">
  	 <tr>
     <td style="background:#C6D7D7;">
	 <table align="right" border="0" cellspacing="0" cellpadding="0" style="background:#C6D7D7; border:2px solid #E7EFEF; padding:5px; margin-top:3px; margin-bottom:3px;">
	 <tr><td colspan="3" align="center"><span style="font-weight:bold; color:#333;"><?php echo $entry_order_created; ?></span></td></tr>
  	 <tr><td>
       <table cellpadding="0" cellspacing="0" style="padding-top:3px;">
       <tr><td align="left"><?php echo $entry_range; ?><br />    
            <select name="filter_range" id="filter_range" class="styled-select-range">
              <?php foreach ($ranges as $range) { ?>
              <?php if ($range['value'] == $filter_range) { ?>
              <option value="<?php echo $range['value']; ?>" title="<?php echo $range['text']; ?>" style="<?php echo $range['style']; ?>" selected="selected"><?php echo $range['text']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $range['value']; ?>" title="<?php echo $range['text']; ?>" style="<?php echo $range['style']; ?>"><?php echo $range['text']; ?></option>
              <?php } ?>
              <?php } ?>
            </select>
       </td><td width="5"></td></tr></table>
     </td><td>      
       <table cellpadding="0" cellspacing="0" style="padding-top:3px;">
       <tr><td align="left">&nbsp;<?php echo $entry_date_start; ?><br />
          <input type="text" name="filter_date_start" value="<?php echo $filter_date_start; ?>" id="date-start" size="12" class="styled-input-range" />
       </td><td width="5"></td></tr></table>
     </td><td>
       <table cellpadding="0" cellspacing="0" style="padding-top:3px;">
       <tr><td align="left">&nbsp;<?php echo $entry_date_end; ?><br />
          <input type="text" name="filter_date_end" value="<?php echo $filter_date_end; ?>" id="date-end" size="12" class="styled-input-range" />
       </td><td></td></tr></table>
     </td></tr></table>  
     </td>
     <td align="center" style="background:#C6D7D7;">      
	 <table border="0" cellspacing="0" cellpadding="0" style="background:#C6D7D7; border:2px solid #E7EFEF; padding:5px; margin-top:3px; margin-bottom:3px;">
	 <tr><td colspan="3" align="center"><span style="font-weight:bold; color:#333;"><?php echo $entry_status_changed; ?></span></td></tr>
  	 <tr><td>
       <table cellpadding="0" cellspacing="0" style="padding-top:3px;">
       <tr><td align="left">&nbsp;<?php echo $entry_date_start; ?><br />
          <input type="text" name="filter_status_date_start" value="<?php echo $filter_status_date_start; ?>" id="status-date-start" size="12" class="styled-input" />
       </td><td width="5"></td></tr></table>
     </td><td> 
       <table cellpadding="0" cellspacing="0" style="padding-top:3px;">
       <tr><td align="left">&nbsp;<?php echo $entry_date_end; ?><br />
          <input type="text" name="filter_status_date_end" value="<?php echo $filter_status_date_end; ?>" id="status-date-end" size="12" class="styled-input" />
       </td><td width="5"></td></tr></table>
     </td><td> 
       <table cellpadding="0" cellspacing="0" style="padding-top:3px;">
       <tr><td align="left"><?php echo $entry_status; ?><br />
          <span <?php echo (!$filter_order_status_id) ? '' : 'class="vtip"' ?> title="<?php foreach ($order_statuses as $order_status) { ?><?php if (isset($filter_order_status_id[$order_status['order_status_id']])) { ?><?php echo $order_status['name']; ?><br /><?php } ?><?php } ?>">
          <select name="filter_order_status_id" id="filter_order_status_id" multiple="multiple" size="1">
            <?php foreach ($order_statuses as $order_status) { ?>
            <?php if (isset($filter_order_status_id[$order_status['order_status_id']])) { ?>
            <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
            <?php } ?>
            <?php } ?>
          </select></span>
       </td></tr></table>
     </td></tr></table>
	 </td>
     <td style="background:#C6D7D7;">        
	 <table align="left" border="0" cellspacing="0" cellpadding="0" style="background:#C6D7D7; border:2px solid #E7EFEF; padding:5px; margin-top:3px; margin-bottom:3px;">
	 <tr><td colspan="2" align="center"><span style="font-weight:bold; color:#333;"><?php echo $entry_order_id; ?></span></td></tr>
  	 <tr><td>  
       <table cellpadding="0" cellspacing="0" style="padding-top:3px;">
       <tr><td align="left">&nbsp;<?php echo $entry_order_id_from; ?><br />
          <input type="text" name="filter_order_id_from" value="<?php echo $filter_order_id_from; ?>" size="12" class="styled-input" />
       </td><td width="5"></td></tr></table>
     </td><td> 
       <table cellpadding="0" cellspacing="0" style="padding-top:3px;">
       <tr><td align="left">&nbsp;<?php echo $entry_order_id_to; ?><br />
          <input type="text" name="filter_order_id_to" value="<?php echo $filter_order_id_to; ?>" size="12" class="styled-input" />
       </td><td></td></tr></table>
    </td></tr></table>
    </td></tr>
	<tr>
    <td colspan="3" valign="top" style="padding:5px;">  
      <table cellpadding="0" cellspacing="0" style="float:left; height:60px;" id="so2_filter">
        <tr><td><span style="font-weight:bold; color:#333;"><?php echo $entry_store; ?></span><br />
          <span <?php echo (!$filter_store_id) ? '' : 'class="vtip"' ?> title="<?php foreach ($stores as $store) { ?><?php if (isset($filter_store_id[$store['store_id']])) { ?><?php echo $store['store_name']; ?><br /><?php } ?><?php } ?>">
          <select name="filter_store_id" id="filter_store_id" multiple="multiple" size="1">
            <?php foreach ($stores as $store) { ?>
            <?php if (isset($filter_store_id[$store['store_id']])) { ?>            
            <option value="<?php echo $store['store_id']; ?>" selected="selected"><?php echo $store['store_name']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $store['store_id']; ?>"><?php echo $store['store_name']; ?></option>
            <?php } ?>
            <?php } ?>
          </select></span></td><td width="15"></td></tr>
        <tr><td>&nbsp;</td><td>&nbsp;</td>
	  </tr></table>    
      <table cellpadding="0" cellspacing="0" style="float:left; height:60px;" id="so3_filter">
        <tr><td><span style="font-weight:bold; color:#333;"><?php echo $entry_currency; ?></span><br />
          <span <?php echo (!$filter_currency) ? '' : 'class="vtip"' ?> title="<?php foreach ($currencies as $currency) { ?><?php if (isset($filter_currency[$currency['currency_id']])) { ?><?php echo $currency['title']; ?> (<?php echo $currency['code']; ?>)<br /><?php } ?><?php } ?>">
          <select name="filter_currency" id="filter_currency" multiple="multiple" size="1">
            <?php foreach ($currencies as $currency) { ?>
            <?php if (isset($filter_currency[$currency['currency_id']])) { ?>
            <option value="<?php echo $currency['currency_id']; ?>" selected="selected"><?php echo $currency['title']; ?> (<?php echo $currency['code']; ?>)</option>
            <?php } else { ?>
            <option value="<?php echo $currency['currency_id']; ?>"><?php echo $currency['title']; ?> (<?php echo $currency['code']; ?>)</option>
            <?php } ?>
            <?php } ?>
          </select></span></td><td width="15"></td></tr>
        <tr><td>&nbsp;</td><td>&nbsp;</td>          
	  </tr></table>
      <table cellpadding="0" cellspacing="0" style="float:left; height:60px;" id="so4a_filter">
        <tr><td><span style="font-weight:bold; color:#333;"><?php echo $entry_tax; ?></span><br />
          <span <?php echo (!$filter_taxes) ? '' : 'class="vtip"' ?> title="<?php foreach ($taxes as $tax) { ?><?php if (isset($filter_taxes[$tax['tax']])) { ?><?php echo $tax['tax_title']; ?><br /><?php } ?><?php } ?>">
		  <select name="filter_taxes" id="filter_taxes" multiple="multiple" size="1">
            <?php foreach ($taxes as $tax) { ?>
            <?php if (isset($filter_taxes[$tax['tax']])) { ?>              
            <option value="<?php echo $tax['tax']; ?>" selected="selected"><?php echo $tax['tax_title']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $tax['tax']; ?>"><?php echo $tax['tax_title']; ?></option>
            <?php } ?>
            <?php } ?>
          </select></span></td><td width="15"></td></tr>
        <tr><td>&nbsp;</td><td>&nbsp;</td>
	  </tr></table>
      <table cellpadding="0" cellspacing="0" style="float:left; height:60px;" id="so4c_filter">
        <tr><td><span style="font-weight:bold; color:#333;"><?php echo $entry_tax_classes; ?></span><br />
          <span <?php echo (!$filter_tax_classes) ? '' : 'class="vtip"' ?> title="<?php foreach ($tax_classes as $tax_class) { ?><?php if (isset($filter_tax_classes[$tax_class['tax_class']])) { ?><?php echo $tax_class['tax_class_title']; ?><br /><?php } ?><?php } ?>">
		  <select name="filter_tax_classes" id="filter_tax_classes" multiple="multiple" size="1">
            <?php foreach ($tax_classes as $tax_class) { ?>
            <?php if (isset($filter_tax_classes[$tax_class['tax_class']])) { ?>              
            <option value="<?php echo $tax_class['tax_class']; ?>" selected="selected"><?php echo $tax_class['tax_class_title']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $tax_class['tax_class']; ?>"><?php echo $tax_class['tax_class_title']; ?></option>
            <?php } ?>
            <?php } ?>
          </select></span></td><td width="15"></td></tr>
        <tr><td>&nbsp;</td><td>&nbsp;</td>
	  </tr></table>      
      <table cellpadding="0" cellspacing="0" style="float:left; height:60px;" id="so4b_filter">
        <tr><td><span style="font-weight:bold; color:#333;"><?php echo $entry_geo_zone; ?></span><br />
          <span <?php echo (!$filter_geo_zones) ? '' : 'class="vtip"' ?> title="<?php foreach ($geo_zones as $geo_zone) { ?><?php if (isset($filter_geo_zones[$geo_zone['geo_zone_country_id']])) { ?><?php echo $geo_zone['geo_zone_name']; ?><br /><?php } ?><?php } ?>">
		  <select name="filter_geo_zones" id="filter_geo_zones" multiple="multiple" size="1">
            <?php foreach ($geo_zones as $geo_zone) { ?>
            <?php if (isset($filter_geo_zones[$geo_zone['geo_zone_country_id']])) { ?>              
            <option value="<?php echo $geo_zone['geo_zone_country_id']; ?>" selected="selected"><?php echo $geo_zone['geo_zone_name']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $geo_zone['geo_zone_country_id']; ?>"><?php echo $geo_zone['geo_zone_name']; ?></option>
            <?php } ?>
            <?php } ?>
          </select></span></td><td width="15"></td></tr>
        <tr><td>&nbsp;</td><td>&nbsp;</td>
	  </tr></table>
      <table cellpadding="0" cellspacing="0" style="float:left; height:60px;" id="so5_filter">
        <tr><td><span style="font-weight:bold; color:#333;"><?php echo $entry_customer_group; ?></span><br />
          <span <?php echo (!$filter_customer_group_id) ? '' : 'class="vtip"' ?> title="<?php foreach ($customer_groups as $customer_group) { ?><?php if (isset($filter_customer_group_id[$customer_group['customer_group_id']])) { ?><?php echo $customer_group['name']; ?><br /><?php } ?><?php } ?>">
          <select name="filter_customer_group_id" id="filter_customer_group_id" multiple="multiple" size="1">
            <?php foreach ($customer_groups as $customer_group) { ?>
            <?php if (isset($filter_customer_group_id[$customer_group['customer_group_id']])) { ?>              
            <option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
            <?php } ?>
            <?php } ?>
          </select></span></td><td width="15"></td></tr>
        <tr><td>&nbsp;</td><td>&nbsp;</td>
	  </tr></table>
      <table cellpadding="0" cellspacing="0" style="float:left; height:60px;" id="so7_filter">
        <tr><td>&nbsp;<span style="font-weight:bold; color:#333;"><?php echo $entry_customer_name; ?></span><br />
        <input type="text" name="filter_customer_name" value="<?php echo $filter_customer_name; ?>" size="20" class="styled-input" onclick="this.value = '';">
		</td><td width="15"></td></tr>
        <tr><td>&nbsp;</td><td>&nbsp;</td>
	  </tr></table>  
      <table cellpadding="0" cellspacing="0" style="float:left; height:60px;" id="so8a_filter">
        <tr><td>&nbsp;<span style="font-weight:bold; color:#333;"><?php echo $entry_customer_email; ?></span><br />
        <input type="text" name="filter_customer_email" value="<?php echo $filter_customer_email; ?>" size="20" class="styled-input" onclick="this.value = '';">
        </td><td width="15"></td></tr>
        <tr><td>&nbsp;</td><td>&nbsp;</td>
	  </tr></table>  
      <table cellpadding="0" cellspacing="0" style="float:left; height:60px;" id="so8b_filter">
        <tr><td>&nbsp;<span style="font-weight:bold; color:#333;"><?php echo $entry_customer_telephone; ?></span><br />
        <input type="text" name="filter_customer_telephone" value="<?php echo $filter_customer_telephone; ?>" size="20" class="styled-input" onclick="this.value = '';">
        </td><td width="15"></td></tr>
        <tr><td>&nbsp;</td><td>&nbsp;</td>
	  </tr></table> 
      <table cellpadding="0" cellspacing="0" style="float:left; height:60px;" id="so8c_filter">
        <tr><td>&nbsp;<span style="font-weight:bold; color:#333;"><?php echo $entry_ip; ?></span><br />
        <input type="text" name="filter_ip" value="<?php echo $filter_ip; ?>" size="20" class="styled-input" onclick="this.value = '';">
        </td><td width="15"></td></tr>
        <tr><td>&nbsp;</td><td>&nbsp;</td>
	  </tr></table>       
      <table cellpadding="0" cellspacing="0" style="float:left; height:60px;" id="so17a_filter">
        <tr><td>&nbsp;<span style="font-weight:bold; color:#333;"><?php echo $entry_payment_company; ?></span><br />
        <input type="text" name="filter_payment_company" value="<?php echo $filter_payment_company; ?>" size="20" class="styled-input" onclick="this.value = '';">
        </td><td width="15"></td></tr>
        <tr><td>&nbsp;</td><td>&nbsp;</td>
	  </tr></table> 
      <table cellpadding="0" cellspacing="0" style="float:left; height:60px;" id="so17b_filter">
        <tr><td>&nbsp;<span style="font-weight:bold; color:#333;"><?php echo $entry_payment_address; ?></span><br />
        <input type="text" name="filter_payment_address" value="<?php echo $filter_payment_address; ?>" size="20" class="styled-input" onclick="this.value = '';">
        </td><td width="15"></td></tr>
        <tr><td>&nbsp;</td><td>&nbsp;</td>
	  </tr></table> 
      <table cellpadding="0" cellspacing="0" style="float:left; height:60px;" id="so17c_filter">
        <tr><td>&nbsp;<span style="font-weight:bold; color:#333;"><?php echo $entry_payment_city; ?></span><br />
        <input type="text" name="filter_payment_city" value="<?php echo $filter_payment_city; ?>" size="20" class="styled-input" onclick="this.value = '';">
		</td><td width="15"></td></tr>
        <tr><td>&nbsp;</td><td>&nbsp;</td>
	  </tr></table> 
      <table cellpadding="0" cellspacing="0" style="float:left; height:60px;" id="so17d_filter">
        <tr><td>&nbsp;<span style="font-weight:bold; color:#333;"><?php echo $entry_payment_zone; ?></span><br />
        <input type="text" name="filter_payment_zone" value="<?php echo $filter_payment_zone; ?>" size="20" class="styled-input" onclick="this.value = '';">
		</td><td width="15"></td></tr>
        <tr><td>&nbsp;</td><td>&nbsp;</td>
	  </tr></table>  
      <table cellpadding="0" cellspacing="0" style="float:left; height:60px;" id="so17e_filter">
        <tr><td>&nbsp;<span style="font-weight:bold; color:#333;"><?php echo $entry_payment_postcode; ?></span><br />
        <input type="text" name="filter_payment_postcode" value="<?php echo $filter_payment_postcode; ?>" size="20" class="styled-input" onclick="this.value = '';">
        </td><td width="15"></td></tr>
        <tr><td>&nbsp;</td><td>&nbsp;</td>
	  </tr></table> 
      <table cellpadding="0" cellspacing="0" style="float:left; height:60px;" id="so17f_filter">
        <tr><td>&nbsp;<span style="font-weight:bold; color:#333;"><?php echo $entry_payment_country; ?></span><br />
        <input type="text" name="filter_payment_country" value="<?php echo $filter_payment_country; ?>" size="20" class="styled-input" onclick="this.value = '';">
        </td><td width="15"></td></tr>
        <tr><td>&nbsp;</td><td>&nbsp;</td>
	  </tr></table> 
      <table cellpadding="0" cellspacing="0" style="float:left; height:60px;" id="so13_filter">
        <tr><td><span style="font-weight:bold; color:#333;"><?php echo $entry_payment_method; ?></span><br />
          <span <?php echo (!$filter_payment_method) ? '' : 'class="vtip"' ?> title="<?php foreach ($payment_methods as $payment_method) { ?><?php if (isset($filter_payment_method[$payment_method['payment_title']])) { ?><?php echo $payment_method['payment_name']; ?><br /><?php } ?><?php } ?>">
		  <select name="filter_payment_method" id="filter_payment_method" multiple="multiple" size="1">
            <?php foreach ($payment_methods as $payment_method) { ?>
            <?php if (isset($filter_payment_method[$payment_method['payment_title']])) { ?>              
            <option value="<?php echo $payment_method['payment_title']; ?>" selected="selected"><?php echo preg_replace('~\(.*?\)~', '', $payment_method['payment_name']); ?></option>
            <?php } else { ?>
            <option value="<?php echo $payment_method['payment_title']; ?>"><?php echo preg_replace('~\(.*?\)~', '', $payment_method['payment_name']); ?></option>
            <?php } ?>
            <?php } ?>
          </select></span></td><td width="15"></td></tr>
        <tr><td>&nbsp;</td><td>&nbsp;</td>
	  </tr></table>   
      <table cellpadding="0" cellspacing="0" style="float:left; height:60px;" id="so16a_filter">
        <tr><td>&nbsp;<span style="font-weight:bold; color:#333;"><?php echo $entry_shipping_company; ?></span><br />
        <input type="text" name="filter_shipping_company" value="<?php echo $filter_shipping_company; ?>" size="20" class="styled-input" onclick="this.value = '';">
        </td><td width="15"></td></tr>
        <tr><td>&nbsp;</td><td>&nbsp;</td>
	  </tr></table> 
      <table cellpadding="0" cellspacing="0" style="float:left; height:60px;" id="so16b_filter">
        <tr><td>&nbsp;<span style="font-weight:bold; color:#333;"><?php echo $entry_shipping_address; ?></span><br />
        <input type="text" name="filter_shipping_address" value="<?php echo $filter_shipping_address; ?>" size="20" class="styled-input" onclick="this.value = '';">
        </td><td width="15"></td></tr>
        <tr><td>&nbsp;</td><td>&nbsp;</td>
	  </tr></table> 
      <table cellpadding="0" cellspacing="0" style="float:left; height:60px;" id="so16c_filter">
        <tr><td>&nbsp;<span style="font-weight:bold; color:#333;"><?php echo $entry_shipping_city; ?></span><br />
        <input type="text" name="filter_shipping_city" value="<?php echo $filter_shipping_city; ?>" size="20" class="styled-input" onclick="this.value = '';">
        </td><td width="15"></td></tr>
        <tr><td>&nbsp;</td><td>&nbsp;</td>
	  </tr></table> 
      <table cellpadding="0" cellspacing="0" style="float:left; height:60px;" id="so16d_filter">
        <tr><td>&nbsp;<span style="font-weight:bold; color:#333;"><?php echo $entry_shipping_zone; ?></span><br />
        <input type="text" name="filter_shipping_zone" value="<?php echo $filter_shipping_zone; ?>" size="20" class="styled-input" onclick="this.value = '';">
        </td><td width="15"></td></tr>
        <tr><td>&nbsp;</td><td>&nbsp;</td>
	  </tr></table>                     
      <table cellpadding="0" cellspacing="0" style="float:left; height:60px;" id="so16e_filter">
        <tr><td>&nbsp;<span style="font-weight:bold; color:#333;"><?php echo $entry_shipping_postcode; ?></span><br />
        <input type="text" name="filter_shipping_postcode" value="<?php echo $filter_shipping_postcode; ?>" size="20" class="styled-input" onclick="this.value = '';">
        </td><td width="15"></td></tr>
        <tr><td>&nbsp;</td><td>&nbsp;</td>
	  </tr></table> 
      <table cellpadding="0" cellspacing="0" style="float:left; height:60px;" id="so16f_filter">
        <tr><td>&nbsp;<span style="font-weight:bold; color:#333;"><?php echo $entry_shipping_country; ?></span><br />
        <input type="text" name="filter_shipping_country" value="<?php echo $filter_shipping_country; ?>" size="20" class="styled-input" onclick="this.value = '';">
        </td><td width="15"></td></tr>
        <tr><td>&nbsp;</td><td>&nbsp;</td>
	  </tr></table>           
      <table cellpadding="0" cellspacing="0" style="float:left; height:60px;" id="so14_filter">
        <tr><td><span style="font-weight:bold; color:#333;"><?php echo $entry_shipping_method; ?></span><br />
          <span <?php echo (!$filter_shipping_method) ? '' : 'class="vtip"' ?> title="<?php foreach ($shipping_methods as $shipping_method) { ?><?php if (isset($filter_shipping_method[$shipping_method['shipping_title']])) { ?><?php echo $shipping_method['shipping_name']; ?><br /><?php } ?><?php } ?>">
		  <select name="filter_shipping_method" id="filter_shipping_method" multiple="multiple" size="1">
            <?php foreach ($shipping_methods as $shipping_method) { ?>
            <?php if (isset($filter_shipping_method[$shipping_method['shipping_title']])) { ?>              
            <option value="<?php echo $shipping_method['shipping_title']; ?>" selected="selected"><?php echo preg_replace('~\(.*?\)~', '', $shipping_method['shipping_name']); ?></option>
            <?php } else { ?>
            <option value="<?php echo $shipping_method['shipping_title']; ?>"><?php echo preg_replace('~\(.*?\)~', '', $shipping_method['shipping_name']); ?></option>
            <?php } ?>
            <?php } ?>
          </select></span></td><td width="15"></td></tr>
        <tr><td>&nbsp;</td><td>&nbsp;</td>
	  </tr></table>
      <table cellpadding="0" cellspacing="0" style="float:left; height:60px;" id="so9d_filter">
        <tr><td><span style="font-weight:bold; color:#333;"><?php echo $entry_category; ?></span><br />
          <span <?php echo (!$filter_category) ? '' : 'class="vtip"' ?> title="<?php foreach ($categories as $category) { ?><?php if (isset($filter_category[$category['category_id']])) { ?><?php echo $category['name']; ?><br /><?php } ?><?php } ?>">
          <select name="filter_category" id="filter_category" multiple="multiple" size="1">
            <?php foreach ($categories as $category) { ?>
            <?php if (isset($filter_category[$category['category_id']])) { ?>               
            <option value="<?php echo $category['category_id']; ?>" selected="selected"><?php echo $category['name']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $category['category_id']; ?>"><?php echo $category['name']; ?></option> 
            <?php } ?>
            <?php } ?>
          </select></span></td><td width="15"></td></tr>
        <tr><td>&nbsp;</td><td>&nbsp;</td>
	  </tr></table>                               
      <table cellpadding="0" cellspacing="0" style="float:left; height:60px;" id="so9e_filter">
        <tr><td><span style="font-weight:bold; color:#333;"><?php echo $entry_manufacturer; ?></span><br />
          <span <?php echo (!$filter_manufacturer) ? '' : 'class="vtip"' ?> title="<?php foreach ($manufacturers as $manufacturer) { ?><?php if (isset($filter_manufacturer[$manufacturer['manufacturer_id']])) { ?> <?php echo $manufacturer['name']; ?><br /><?php } ?><?php } ?>">
          <select name="filter_manufacturer" id="filter_manufacturer" multiple="multiple" size="1">
            <?php foreach ($manufacturers as $manufacturer) { ?>
            <?php if (isset($filter_manufacturer[$manufacturer['manufacturer_id']])) { ?>               
            <option value="<?php echo $manufacturer['manufacturer_id']; ?>" selected="selected"><?php echo $manufacturer['name']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $manufacturer['manufacturer_id']; ?>"><?php echo $manufacturer['name']; ?></option> 
            <?php } ?>
            <?php } ?>
          </select></span></td><td width="15"></td></tr>
        <tr><td>&nbsp;</td><td>&nbsp;</td>
	  </tr></table>            
      <table cellpadding="0" cellspacing="0" style="float:left; height:60px;" id="so9a_filter">
        <tr><td>&nbsp;<span style="font-weight:bold; color:#333;"><?php echo $entry_sku; ?></span><br />
        <input type="text" name="filter_sku" value="<?php echo $filter_sku; ?>" size="20" class="styled-input" onclick="this.value = '';">
        </td><td width="15"></td></tr>
        <tr><td>&nbsp;</td><td>&nbsp;</td>
	  </tr></table> 
      <table cellpadding="0" cellspacing="0" style="float:left; height:60px;" id="so9b_filter">
        <tr><td>&nbsp;<span style="font-weight:bold; color:#333;"><?php echo $entry_product; ?></span><br />
        <input type="text" name="filter_product_id" value="<?php echo $filter_product_id; ?>" size="40" class="styled-input" onclick="this.value = '';">
        </td><td width="15"></td></tr>
        <tr><td>&nbsp;</td><td>&nbsp;</td>
	  </tr></table> 
      <table cellpadding="0" cellspacing="0" style="float:left; height:60px;" id="so9c_filter">
        <tr><td>&nbsp;<span style="font-weight:bold; color:#333;"><?php echo $entry_model; ?></span><br />
        <input type="text" name="filter_model" value="<?php echo $filter_model; ?>" size="20" class="styled-input" onclick="this.value = '';">
        </td><td width="15"></td></tr>
        <tr><td>&nbsp;</td><td>&nbsp;</td>
	  </tr></table>  
	  <table cellpadding="0" cellspacing="0" style="float:left; height:60px;" id="so10_filter">
        <tr><td><span style="font-weight:bold; color:#333;"><?php echo $entry_option; ?></span><br />
          <span <?php echo (!$filter_option) ? '' : 'class="vtip"' ?> title="<?php foreach ($order_options as $order_option) { ?><?php if (isset($filter_option[$order_option['options']])) { ?><?php echo $order_option['option_name']; ?>: <?php echo $order_option['option_value']; ?><br /><?php } ?><?php } ?>">        
          <select name="filter_option" id="filter_option" multiple="multiple" size="1">
            <?php foreach ($order_options as $order_option) { ?>
            <?php if (isset($filter_option[$order_option['options']])) { ?>              
            <option value="<?php echo $order_option['options']; ?>" selected="selected"><?php echo $order_option['option_name']; ?>: <?php echo $order_option['option_value']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $order_option['options']; ?>"><?php echo $order_option['option_name']; ?>: <?php echo $order_option['option_value']; ?></option>
            <?php } ?>
            <?php } ?>
          </select></span></td><td width="15"></td></tr>
        <tr><td>&nbsp;</td><td>&nbsp;</td>
	  </tr></table>  
      <table cellpadding="0" cellspacing="0" style="float:left; height:60px;" id="so18_filter">
        <tr><td><span style="font-weight:bold; color:#333;"><?php echo $entry_attributes; ?></span><br />
          <span <?php echo (!$filter_attribute) ? '' : 'class="vtip"' ?> title="<?php foreach ($attributes as $attribute) { ?><?php if (isset($filter_attribute[$attribute['attribute_title']])) { ?><?php echo $attribute['attribute_name']; ?><br /><?php } ?><?php } ?>">
		  <select name="filter_attribute" id="filter_attribute" multiple="multiple" size="1">
            <?php foreach ($attributes as $attribute) { ?>
            <?php if (isset($filter_attribute[$attribute['attribute_title']])) { ?>              
            <option value="<?php echo $attribute['attribute_title']; ?>" selected="selected"><?php echo $attribute['attribute_name']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $attribute['attribute_title']; ?>"><?php echo $attribute['attribute_name']; ?></option>
            <?php } ?>
            <?php } ?>
          </select></span></td><td width="15"></td></tr>
        <tr><td>&nbsp;</td><td>&nbsp;</td>
	  </tr></table>                    
      <table cellpadding="0" cellspacing="0" style="float:left; height:60px;" id="so11_filter">
        <tr><td><span style="font-weight:bold; color:#333;"><?php echo $entry_location; ?></span><br />
          <span <?php echo (!$filter_location) ? '' : 'class="vtip"' ?> title="<?php foreach ($locations as $location) { ?><?php if (isset($filter_location[$location['location_title']])) { ?><?php echo $location['location_name']; ?><br /><?php } ?><?php } ?>">
		  <select name="filter_location" id="filter_location" multiple="multiple" size="1">
            <?php foreach ($locations as $location) { ?>
            <?php if (isset($filter_location[$location['location_title']])) { ?>              
            <option value="<?php echo $location['location_title']; ?>" selected="selected"><?php echo $location['location_name']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $location['location_title']; ?>"><?php echo $location['location_name']; ?></option>
            <?php } ?>
            <?php } ?>
          </select></span></td><td width="15"></td></tr>
        <tr><td>&nbsp;</td><td>&nbsp;</td>
	  </tr></table>
	  <table cellpadding="0" cellspacing="0" style="float:left; height:60px;" id="so12a_filter">
        <tr><td><span style="font-weight:bold; color:#333;"><?php echo $entry_affiliate_name; ?></span><br />
          <span <?php echo (!$filter_affiliate_name) ? '' : 'class="vtip"' ?> title="<?php foreach ($affiliate_names as $affiliate_name) { ?><?php if (isset($filter_affiliate_name[$affiliate_name['affiliate_id']])) { ?><?php echo $affiliate_name['affiliate_name']; ?><br /><?php } ?><?php } ?>">        
          <select name="filter_affiliate_name" id="filter_affiliate_name" multiple="multiple" size="1">
            <?php foreach ($affiliate_names as $affiliate_name) { ?>
            <?php if (isset($filter_affiliate_name[$affiliate_name['affiliate_id']])) { ?>              
            <option value="<?php echo $affiliate_name['affiliate_id']; ?>" selected="selected"><?php echo $affiliate_name['affiliate_name']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $affiliate_name['affiliate_id']; ?>"><?php echo $affiliate_name['affiliate_name']; ?></option>
            <?php } ?>
            <?php } ?>
          </select></span></td><td width="15"></td></tr>
        <tr><td>&nbsp;</td><td>&nbsp;</td>
	  </tr></table>
	  <table cellpadding="0" cellspacing="0" style="float:left; height:60px;" id="so12b_filter">
        <tr><td><span style="font-weight:bold; color:#333;"><?php echo $entry_affiliate_email; ?></span><br />
          <span <?php echo (!$filter_affiliate_email) ? '' : 'class="vtip"' ?> title="<?php foreach ($affiliate_emails as $affiliate_email) { ?><?php if (isset($filter_affiliate_email[$affiliate_email['affiliate_id']])) { ?><?php echo $affiliate_email['affiliate_email']; ?><br /><?php } ?><?php } ?>">
          <select name="filter_affiliate_email" id="filter_affiliate_email" multiple="multiple" size="1">
            <?php foreach ($affiliate_emails as $affiliate_email) { ?>
            <?php if (isset($filter_affiliate_email[$affiliate_email['affiliate_id']])) { ?>              
            <option value="<?php echo $affiliate_email['affiliate_id']; ?>" selected="selected"><?php echo $affiliate_email['affiliate_email']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $affiliate_email['affiliate_id']; ?>"><?php echo $affiliate_email['affiliate_email']; ?></option>
            <?php } ?>
            <?php } ?>
          </select></span></td><td width="15"></td></tr>
        <tr><td>&nbsp;</td><td>&nbsp;</td>
	  </tr></table>
	  <table cellpadding="0" cellspacing="0" style="float:left; height:60px;" id="so15a_filter">
        <tr><td><span style="font-weight:bold; color:#333;"><?php echo $entry_coupon_name; ?></span><br />
          <span <?php echo (!$filter_coupon_name) ? '' : 'class="vtip"' ?> title="<?php foreach ($coupon_names as $coupon_name) { ?><?php if (isset($filter_coupon_name[$coupon_name['coupon_id']])) { ?><?php echo $coupon_name['coupon_name']; ?><br /><?php } ?><?php } ?>">        
          <select name="filter_coupon_name" id="filter_coupon_name" multiple="multiple" size="1">
            <?php foreach ($coupon_names as $coupon_name) { ?>
            <?php if (isset($filter_coupon_name[$coupon_name['coupon_id']])) { ?>              
            <option value="<?php echo $coupon_name['coupon_id']; ?>" selected="selected"><?php echo $coupon_name['coupon_name']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $coupon_name['coupon_id']; ?>"><?php echo $coupon_name['coupon_name']; ?></option>
            <?php } ?>
            <?php } ?>
          </select></span></td><td width="15"></td></tr>
        <tr><td>&nbsp;</td><td>&nbsp;</td>
	  </tr></table>
      <table cellpadding="0" cellspacing="0" style="float:left; height:60px;" id="so15b_filter">
        <tr><td>&nbsp;<span style="font-weight:bold; color:#333;"><?php echo $entry_coupon_code; ?></span><br />
        <input type="text" name="filter_coupon_code" value="<?php echo $filter_coupon_code; ?>" size="20" class="styled-input" onclick="this.value = '';">
        </td><td width="15"></td></tr>
        <tr><td>&nbsp;</td><td>&nbsp;</td>
	  </tr></table>  
      <table cellpadding="0" cellspacing="0" style="float:left; height:60px;" id="so19_filter">
        <tr><td>&nbsp;<span style="font-weight:bold; color:#333;"><?php echo $entry_voucher_code; ?></span><br />
        <input type="text" name="filter_voucher_code" value="<?php echo $filter_voucher_code; ?>" size="20" class="styled-input" onclick="this.value = '';">
        </td><td width="15"></td></tr>
        <tr><td>&nbsp;</td><td>&nbsp;</td>
	  </tr></table>  
	   </td>
	  </tr>
	 </table>
	</td>
	</tr>
	</table>      
</div>
<?php if ($orders) { ?>
<script type="text/javascript">$(function(){ 
$('#show_tab_chart').click(function() {
		$('#tab_chart').slideToggle('slow');
	});
});
</script>  
	<?php if ($filter_report == 'sales_summary') { ?> 
	<?php if ($filter_details != '4' && ($filter_group == 'year' or $filter_group == 'quarter' or $filter_group == 'month')) { ?>     
    <div id="tab_chart">
      <table align="center" cellspacing="0" cellpadding="3">
        <tr>
          <td><div style="float:left;" id="chart1_sales_summary"></div><div style="float:left;" id="chart2_sales_summary"></div></td>
        </tr>
      </table>
    </div>
	<?php } ?> 
	<?php } elseif ($filter_report == 'day_of_week') { ?>
    <div id="tab_chart">
      <table align="center" cellspacing="0" cellpadding="3">    
        <tr>
          <td><div style="float:left;" id="chart1_day_of_week"></div><div style="float:left;" id="chart2_day_of_week"></div></td>
        </tr>
      </table>
    </div>
    <?php } elseif ($filter_report == 'hour') { ?>
    <div id="tab_chart">
      <table align="center" cellspacing="0" cellpadding="3">    
        <tr>
          <td><div style="float:left;" id="chart1_hour"></div><div style="float:left;" id="chart2_hour"></div></td>
        </tr>
      </table>
    </div>
	<?php } elseif ($filter_report == 'store') { ?>
    <div id="tab_chart">
      <table align="center" cellspacing="0" cellpadding="3">    
        <tr>
          <td><div style="float:left;" id="chart1_store"></div><div style="float:left;" id="chart2_store"></div></td>
        </tr>
      </table>
    </div>
	<?php } elseif ($filter_report == 'customer_group') { ?>
    <div id="tab_chart">
      <table align="center" cellspacing="0" cellpadding="3">    
        <tr>
          <td><div style="float:left;" id="chart1_customer_group"></div><div style="float:left;" id="chart2_customer_group"></div></td>
        </tr>
      </table>
    </div>
	<?php } elseif ($filter_report == 'country') { ?>
    <div id="tab_chart">
      <table align="center" cellspacing="0" cellpadding="3">
        <tr>
          <td><div style="float:center;" id="chart_country"></div></td>
        </tr>
      </table>
    </div> 
	<?php } elseif ($filter_report == 'payment_method') { ?>
    <div id="tab_chart">
      <table align="center" cellspacing="0" cellpadding="3">
        <tr>
          <td><div style="float:center; width:625px; height:350px;" id="chart_payment_method"></div></td>
        </tr>
      </table>
    </div>   
	<?php } elseif ($filter_report == 'shipping_method') { ?>
    <div id="tab_chart">
      <table align="center" cellspacing="0" cellpadding="3">
        <tr>
          <td><div style="float:center; width:625px; height:350px;" id="chart_shipping_method"></div></td>
        </tr>
      </table>
    </div>   
	<?php } ?>
	<?php } ?> 
	<?php if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false) { ?>
    <div id="pagination_content" style="overflow:scroll; padding:1px;"> 
    <?php } else { ?>
    <div id="pagination_content" style="overflow:auto; padding:1px;">     
    <?php } ?>
<?php if ($filter_details == 4) { ?>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">       
	<tr> 
    <td> 
	<?php if ($orders) { ?>
	<?php foreach ($orders as $order) { ?>
    <?php if ($order['product_pidc']) { ?>           
		<table class="list_detail" id="element" style="border-bottom:2px solid #999; border-top:2px solid #999;">
		<thead>
		<tr>
          <td class="left" nowrap="nowrap"><?php echo $column_order_order_id; ?></td>        
          <td class="left" nowrap="nowrap"><?php echo $column_order_date_added; ?></td> 
          <td id="so1000_<?php echo $order['order_id']; ?>_title" class="left"><?php echo $column_order_inv_no; ?></td>
          <td id="so1001_<?php echo $order['order_id']; ?>_title" class="left"><?php echo $column_order_customer; ?></td>
          <td id="so1002_<?php echo $order['order_id']; ?>_title" class="left"><?php echo $column_order_email; ?></td>
          <td id="so1003_<?php echo $order['order_id']; ?>_title" class="left"><?php echo $column_order_customer_group; ?></td>
          <td id="so1040_<?php echo $order['order_id']; ?>_title" class="left"><?php echo $column_order_shipping_method; ?></td>
          <td id="so1041_<?php echo $order['order_id']; ?>_title" class="left"><?php echo $column_order_payment_method; ?></td>          
          <td id="so1042_<?php echo $order['order_id']; ?>_title" class="left"><?php echo $column_order_status; ?></td>
          <td id="so1043_<?php echo $order['order_id']; ?>_title" class="left"><?php echo $column_order_store; ?></td>
          <td id="so1012_<?php echo $order['order_id']; ?>_title" class="right"><?php echo $column_order_currency; ?></td>
          <td id="so1062_<?php echo $order['order_id']; ?>_title" class="right"><?php echo $column_order_quantity; ?></td>  
          <td id="so1020_<?php echo $order['order_id']; ?>_title" class="right"><?php echo $column_order_sub_total; ?></td>                               
          <td id="so1023_<?php echo $order['order_id']; ?>_title" class="right"><?php echo $column_order_shipping; ?></td>         
          <td id="so1027_<?php echo $order['order_id']; ?>_title" class="right"><?php echo $column_order_tax; ?></td>
          <td id="so1031_<?php echo $order['order_id']; ?>_title" class="right"><?php echo $column_order_value; ?></td>        
		</tr>
		</thead>
        <tbody>
		<tr bgcolor="#FFFFFF">
          <td class="left" nowrap="nowrap" style="background-color:#FFC;"><a><?php echo $order['order_ord_id']; ?></a></td>        
          <td class="left" nowrap="nowrap" style="background-color:#FFC;"><?php echo $order['order_order_date']; ?></td>
          <td id="so1000_<?php echo $order['order_id']; ?>" class="left" nowrap="nowrap"><?php echo $order['order_inv_no']; ?></td>
          <td id="so1001_<?php echo $order['order_id']; ?>" class="left" nowrap="nowrap"><?php echo $order['order_name']; ?></td>
          <td id="so1002_<?php echo $order['order_id']; ?>" class="left" nowrap="nowrap"><?php echo $order['order_email']; ?></td>
          <td id="so1003_<?php echo $order['order_id']; ?>" class="left" nowrap="nowrap"><?php echo $order['order_group']; ?></td>
          <td id="so1040_<?php echo $order['order_id']; ?>" class="left" nowrap="nowrap"><?php echo $order['order_shipping_method']; ?></td>
          <td id="so1041_<?php echo $order['order_id']; ?>" class="left" nowrap="nowrap"><?php echo $order['order_payment_method']; ?></td>          
          <td id="so1042_<?php echo $order['order_id']; ?>" class="left" nowrap="nowrap"><?php echo $order['order_status']; ?></td>
          <td id="so1043_<?php echo $order['order_id']; ?>" class="left" nowrap="nowrap"><?php echo $order['order_store']; ?></td> 
          <td id="so1012_<?php echo $order['order_id']; ?>" class="right" nowrap="nowrap"><?php echo $order['order_currency']; ?></td>          
          <td id="so1062_<?php echo $order['order_id']; ?>" class="right" nowrap="nowrap"><?php echo $order['order_products']; ?></td> 
          <td id="so1020_<?php echo $order['order_id']; ?>" class="right" nowrap="nowrap"><?php echo $order['order_sub_total']; ?></td>                    
          <td id="so1023_<?php echo $order['order_id']; ?>" class="right" nowrap="nowrap"><?php echo $order['order_shipping']; ?></td>           
          <td id="so1027_<?php echo $order['order_id']; ?>" class="right" nowrap="nowrap"><?php echo $order['order_tax']; ?></td>                              
          <td id="so1031_<?php echo $order['order_id']; ?>" class="right" nowrap="nowrap"><?php echo $order['order_value']; ?></td>    
		</tr>  
		<tr>
		<td colspan="16">
		  <table class="list_detail">
          <thead>
          <tr>
          <td id="so1004_<?php echo $order['order_id']; ?>_title" class="left"><?php echo $column_prod_id; ?></td>                                          
		  <td id="so1005_<?php echo $order['order_id']; ?>_title" class="left"><?php echo $column_prod_sku; ?></td>
		  <td id="so1006_<?php echo $order['order_id']; ?>_title" class="left"><?php echo $column_prod_model; ?></td>            
          <td id="so1007_<?php echo $order['order_id']; ?>_title" class="left"><?php echo $column_prod_name; ?></td> 
          <td id="so1008_<?php echo $order['order_id']; ?>_title" class="left"><?php echo $column_prod_option; ?></td>           
          <td id="so1009_<?php echo $order['order_id']; ?>_title" class="left"><?php echo $column_prod_attributes; ?></td>                      
          <td id="so1010_<?php echo $order['order_id']; ?>_title" class="left"><?php echo $column_prod_manu; ?></td> 
          <td id="so1011_<?php echo $order['order_id']; ?>_title" class="left"><?php echo $column_prod_category; ?></td>           
          <td id="so1013_<?php echo $order['order_id']; ?>_title" class="right"><?php echo $column_prod_price; ?></td>                     
          <td id="so1014_<?php echo $order['order_id']; ?>_title" class="right"><?php echo $column_prod_quantity; ?></td>
          <td id="so1016a_<?php echo $order['order_id']; ?>_title" class="right"><?php echo $column_prod_total_excl_vat; ?></td>          
          <td id="so1015_<?php echo $order['order_id']; ?>_title" class="right"><?php echo $column_prod_tax; ?></td>           
          <td id="so1016b_<?php echo $order['order_id']; ?>_title" class="right"><?php echo $column_prod_total_incl_vat; ?></td> 
          </tr>
          </thead>
          <tr bgcolor="#FFFFFF">
          <td id="so1004_<?php echo $order['order_id']; ?>" class="left" nowrap="nowrap"><?php echo $order['product_pid']; ?></td>  
          <td id="so1005_<?php echo $order['order_id']; ?>" class="left" nowrap="nowrap"><?php echo $order['product_sku']; ?></td>
          <td id="so1006_<?php echo $order['order_id']; ?>" class="left" nowrap="nowrap"><?php echo $order['product_model']; ?></td>                 
          <td id="so1007_<?php echo $order['order_id']; ?>" class="left" nowrap="nowrap"><?php echo $order['product_name']; ?></td> 
          <td id="so1008_<?php echo $order['order_id']; ?>" class="left" nowrap="nowrap"><?php echo $order['product_option']; ?></td>            
          <td id="so1009_<?php echo $order['order_id']; ?>" class="left" nowrap="nowrap"><?php echo $order['product_attributes']; ?></td>                    
          <td id="so1010_<?php echo $order['order_id']; ?>" class="left" nowrap="nowrap"><?php echo $order['product_manu']; ?></td> 
          <td id="so1011_<?php echo $order['order_id']; ?>" class="left" nowrap="nowrap"><?php echo $order['product_category']; ?></td> 
          <td id="so1013_<?php echo $order['order_id']; ?>" class="right" nowrap="nowrap"><?php echo $order['product_price']; ?></td> 
          <td id="so1014_<?php echo $order['order_id']; ?>" class="right" nowrap="nowrap"><?php echo $order['product_quantity']; ?></td>
          <td id="so1016a_<?php echo $order['order_id']; ?>" class="right" nowrap="nowrap"><?php echo $order['product_total_excl_vat']; ?></td>  
          <td id="so1015_<?php echo $order['order_id']; ?>" class="right" nowrap="nowrap"><?php echo $order['product_tax']; ?></td>  
          <td id="so1016b_<?php echo $order['order_id']; ?>" class="right" nowrap="nowrap"><?php echo $order['product_total_incl_vat']; ?></td>
          </tr>                  
	      </table>
          <table class="list_detail">
          <thead>
          <tr>
          <td id="so1044_<?php echo $order['order_id']; ?>_title" class="left"><?php echo $column_customer_cust_id; ?></td>           
          <td id="so1045_<?php echo $order['order_id']; ?>_title" class="left"><?php echo $column_billing_name; ?></td> 
          <td id="so1046_<?php echo $order['order_id']; ?>_title" class="left"><?php echo $column_billing_company; ?></td> 
          <td id="so1047_<?php echo $order['order_id']; ?>_title" class="left"><?php echo $column_billing_address_1; ?></td> 
          <td id="so1048_<?php echo $order['order_id']; ?>_title" class="left"><?php echo $column_billing_address_2; ?></td> 
          <td id="so1049_<?php echo $order['order_id']; ?>_title" class="left"><?php echo $column_billing_city; ?></td>
          <td id="so1050_<?php echo $order['order_id']; ?>_title" class="left"><?php echo $column_billing_zone; ?></td> 
          <td id="so1051_<?php echo $order['order_id']; ?>_title" class="left"><?php echo $column_billing_postcode; ?></td>
          <td id="so1052_<?php echo $order['order_id']; ?>_title" class="left"><?php echo $column_billing_country; ?></td>
          <td id="so1053_<?php echo $order['order_id']; ?>_title" class="left"><?php echo $column_customer_telephone; ?></td>
          <td id="so1054_<?php echo $order['order_id']; ?>_title" class="left"><?php echo $column_shipping_name; ?></td> 
          <td id="so1055_<?php echo $order['order_id']; ?>_title" class="left"><?php echo $column_shipping_company; ?></td> 
          <td id="so1056_<?php echo $order['order_id']; ?>_title" class="left"><?php echo $column_shipping_address_1; ?></td> 
          <td id="so1057_<?php echo $order['order_id']; ?>_title" class="left"><?php echo $column_shipping_address_2; ?></td> 
          <td id="so1058_<?php echo $order['order_id']; ?>_title" class="left"><?php echo $column_shipping_city; ?></td>
          <td id="so1059_<?php echo $order['order_id']; ?>_title" class="left"><?php echo $column_shipping_zone; ?></td> 
          <td id="so1060_<?php echo $order['order_id']; ?>_title" class="left"><?php echo $column_shipping_postcode; ?></td>
          <td id="so1061_<?php echo $order['order_id']; ?>_title" class="left"><?php echo $column_shipping_country; ?></td>          
          </tr>
          </thead>
          <tr bgcolor="#FFFFFF">
          <td id="so1044_<?php echo $order['order_id']; ?>" class="left" nowrap="nowrap"><?php echo $order['customer_cust_id']; ?></td>             
          <td id="so1045_<?php echo $order['order_id']; ?>" class="left" nowrap="nowrap"><?php echo $order['billing_name']; ?></td>         
          <td id="so1046_<?php echo $order['order_id']; ?>" class="left" nowrap="nowrap"><?php echo $order['billing_company']; ?></td> 
          <td id="so1047_<?php echo $order['order_id']; ?>" class="left" nowrap="nowrap"><?php echo $order['billing_address_1']; ?></td> 
          <td id="so1048_<?php echo $order['order_id']; ?>" class="left" nowrap="nowrap"><?php echo $order['billing_address_2']; ?></td> 
          <td id="so1049_<?php echo $order['order_id']; ?>" class="left" nowrap="nowrap"><?php echo $order['billing_city']; ?></td> 
          <td id="so1050_<?php echo $order['order_id']; ?>" class="left" nowrap="nowrap"><?php echo $order['billing_zone']; ?></td> 
          <td id="so1051_<?php echo $order['order_id']; ?>" class="left" nowrap="nowrap"><?php echo $order['billing_postcode']; ?></td>                    
          <td id="so1052_<?php echo $order['order_id']; ?>" class="left" nowrap="nowrap"><?php echo $order['billing_country']; ?></td>
          <td id="so1053_<?php echo $order['order_id']; ?>" class="left" nowrap="nowrap"><?php echo $order['customer_telephone']; ?></td> 
          <td id="so1054_<?php echo $order['order_id']; ?>" class="left" nowrap="nowrap"><?php echo $order['shipping_name']; ?></td>         
          <td id="so1055_<?php echo $order['order_id']; ?>" class="left" nowrap="nowrap"><?php echo $order['shipping_company']; ?></td> 
          <td id="so1056_<?php echo $order['order_id']; ?>" class="left" nowrap="nowrap"><?php echo $order['shipping_address_1']; ?></td> 
          <td id="so1057_<?php echo $order['order_id']; ?>" class="left" nowrap="nowrap"><?php echo $order['shipping_address_2']; ?></td> 
          <td id="so1058_<?php echo $order['order_id']; ?>" class="left" nowrap="nowrap"><?php echo $order['shipping_city']; ?></td> 
          <td id="so1059_<?php echo $order['order_id']; ?>" class="left" nowrap="nowrap"><?php echo $order['shipping_zone']; ?></td> 
          <td id="so1060_<?php echo $order['order_id']; ?>" class="left" nowrap="nowrap"><?php echo $order['shipping_postcode']; ?></td>                    
          <td id="so1061_<?php echo $order['order_id']; ?>" class="left" nowrap="nowrap"><?php echo $order['shipping_country']; ?></td>  
          </tr>                  
	      </table>            
		</td>               
		</tr> 
    	</tbody>         
		</table>       
	<?php } ?>
	<?php } ?> 
	<?php } else { ?>
		<table width="100%">    
		<tr>
		<td align="center"><?php echo $text_no_results; ?></td>
		</tr>
        </table>          
	<?php } ?>      
    </td></tr>
    </table>
<br />     
<?php } ?>
<?php if ($filter_details != '4') { ?>               
    <table class="list_main">
        <thead>
          <tr>
          <?php if ($filter_report == 'sales_summary') { ?> 
		  <?php if ($filter_group == 'year') { ?>           
          <td class="left" colspan="2" nowrap="nowrap"><?php echo $column_year; ?></td>
		  <?php } elseif ($filter_group == 'quarter') { ?> 
          <td class="left" nowrap="nowrap"><?php echo $column_year; ?></td>
          <td class="left" nowrap="nowrap"><?php echo $column_quarter; ?></td>       
		  <?php } elseif ($filter_group == 'month') { ?> 
          <td class="left" nowrap="nowrap"><?php echo $column_year; ?></td>
          <td class="left" nowrap="nowrap"><?php echo $column_month; ?></td> 
		  <?php } elseif ($filter_group == 'day') { ?> 
          <td class="left" colspan="2" nowrap="nowrap"><?php echo $column_date; ?></td>
		  <?php } elseif ($filter_group == 'order') { ?> 
          <td class="left" nowrap="nowrap"><?php echo $column_order_order_id; ?></td>
          <td class="left" nowrap="nowrap"><?php echo $column_order_date_added; ?></td>             
		  <?php } else { ?>    
          <td class="left" width="70" nowrap="nowrap"><?php echo $column_date_start; ?></td>
          <td class="left" width="70" nowrap="nowrap"><?php echo $column_date_end; ?></td>           
		  <?php } ?> 
		  <?php } elseif ($filter_report == 'day_of_week') { ?>
          <td class="left" colspan="2" nowrap="nowrap"><?php echo $column_day_of_week; ?></td>
		  <?php } elseif ($filter_report == 'hour') { ?>
          <td class="left" colspan="2" nowrap="nowrap"><?php echo $column_hour; ?></td>  
		  <?php } elseif ($filter_report == 'store') { ?>
          <td class="left" colspan="2" nowrap="nowrap"><?php echo $column_store; ?></td>  
		  <?php } elseif ($filter_report == 'customer_group') { ?>
          <td class="left" colspan="2" nowrap="nowrap"><?php echo $column_customer_group; ?></td>  
		  <?php } elseif ($filter_report == 'country') { ?>
          <td class="left" colspan="2" nowrap="nowrap"><?php echo $column_country; ?></td>  
		  <?php } elseif ($filter_report == 'postcode') { ?>
          <td class="left" colspan="2" nowrap="nowrap"><?php echo $column_postcode; ?></td>  
		  <?php } elseif ($filter_report == 'region_state') { ?>
          <td class="left" colspan="2" nowrap="nowrap"><?php echo $column_region_state; ?></td>  
		  <?php } elseif ($filter_report == 'city') { ?>
          <td class="left" colspan="2" nowrap="nowrap"><?php echo $column_city; ?></td>  
		  <?php } elseif ($filter_report == 'payment_method') { ?>
          <td class="left" colspan="2" nowrap="nowrap"><?php echo $column_payment_method; ?></td>
		  <?php } elseif ($filter_report == 'shipping_method') { ?>
          <td class="left" colspan="2" nowrap="nowrap"><?php echo $column_shipping_method; ?></td>                                                                                                            
		  <?php } ?>
          <td id="so20_title" class="right"><?php echo $column_orders; ?></td>
          <td id="so21_title" class="right"><?php echo $column_customers; ?></td>    
          <td id="so22_title" class="right"><?php echo $column_products; ?></td>
          <td id="so23_title" class="right"><?php echo $column_sub_total; ?></td>
          <td id="so24_title" class="right"><?php echo $column_handling; ?></td>
          <td id="so25_title" class="right"><?php echo $column_loworder; ?></td>
          <td id="so27_title" class="right"><?php echo $column_shipping; ?></td>          
          <td id="so26_title" class="right"><?php echo $column_reward; ?></td>
          <td id="so28_title" class="right"><?php echo $column_coupon; ?></td>          
          <td id="so29_title" class="right"><?php echo $column_tax; ?></td>
          <td id="so30_title" class="right"><?php echo $column_credit; ?></td>           
          <td id="so31_title" class="right"><?php echo $column_voucher; ?></td>
          <td id="so32_title" class="right"><?php echo $column_commission; ?></td>
          <td id="so33_title" class="right"><?php echo $column_total; ?></td>
          <?php if ($filter_details == 1 OR $filter_details == 2 OR $filter_details == 3) { ?><td class="right" nowrap="nowrap"><?php echo $column_action; ?></td><?php } ?> 
          </tr>
      	  </thead>
          <?php if ($orders) { ?>
          <?php foreach ($orders as $order) { ?>
      	  <tbody id="element">
          <tr <?php echo ($filter_details == 1 OR $filter_details == 2 OR $filter_details == 3) ? 'style="cursor:pointer;" title="' . $text_detail . '"' : '' ?> id="show_details_<?php echo $order['order_id']; ?>">          
          <?php if ($filter_report == 'sales_summary') { ?>        
		  <?php if ($filter_group == 'year') { ?>           
          <td class="left" colspan="2" nowrap="nowrap" style="background-color:#F0F0F0;"><?php echo $order['year']; ?></td>
		  <?php } elseif ($filter_group == 'quarter') { ?> 
          <td class="left" nowrap="nowrap" style="background-color:#F0F0F0;"><?php echo $order['year']; ?></td>
          <td class="left" nowrap="nowrap" style="background-color:#F0F0F0;"><?php echo $order['quarter']; ?></td>  
		  <?php } elseif ($filter_group == 'month') { ?> 
          <td class="left" nowrap="nowrap" style="background-color:#F0F0F0;"><?php echo $order['year']; ?></td>
          <td class="left" nowrap="nowrap" style="background-color:#F0F0F0;"><?php echo $order['month']; ?></td>
		  <?php } elseif ($filter_group == 'day') { ?> 
          <td class="left" colspan="2" nowrap="nowrap" style="background-color:#F0F0F0;"><?php echo $order['date_start']; ?></td>
		  <?php } elseif ($filter_group == 'order') { ?> 
          <td class="left" nowrap="nowrap" style="background-color:#F0F0F0;"><?php echo $order['order_id']; ?></td>
          <td class="left" nowrap="nowrap" style="background-color:#F0F0F0;"><?php echo $order['date_start']; ?></td>         
		  <?php } else { ?>    
          <td class="left" nowrap="nowrap" style="background-color:#F0F0F0;"><?php echo $order['date_start']; ?></td>
          <td class="left" nowrap="nowrap" style="background-color:#F0F0F0;"><?php echo $order['date_end']; ?></td>         
		  <?php } ?>
		  <?php } elseif ($filter_report == 'day_of_week') { ?>
          <td class="left" colspan="2" nowrap="nowrap" style="background-color:#F0F0F0;"><?php echo $order['day_of_week']; ?></td> 
		  <?php } elseif ($filter_report == 'hour') { ?>    
          <td class="left" colspan="2" nowrap="nowrap" style="background-color:#F0F0F0;"><?php echo $order['hour']; ?></td>  
		  <?php } elseif ($filter_report == 'store') { ?>    
          <td class="left" colspan="2" nowrap="nowrap" style="background-color:#F0F0F0;"><?php echo $order['store_name']; ?></td> 
		  <?php } elseif ($filter_report == 'customer_group') { ?>    
          <td class="left" colspan="2" nowrap="nowrap" style="background-color:#F0F0F0;"><?php echo $order['customer_group']; ?></td> 
		  <?php } elseif ($filter_report == 'country') { ?>    
          <td class="left" colspan="2" nowrap="nowrap" style="background-color:#F0F0F0;"><?php echo $order['payment_country']; ?></td> 
		  <?php } elseif ($filter_report == 'postcode') { ?>    
          <td class="left" colspan="2" nowrap="nowrap" style="background-color:#F0F0F0;"><?php echo $order['payment_postcode']; ?></td> 
		  <?php } elseif ($filter_report == 'region_state') { ?>    
          <td class="left" colspan="2" nowrap="nowrap" style="background-color:#F0F0F0;"><?php echo $order['payment_zone']; ?></td> 
		  <?php } elseif ($filter_report == 'city') { ?>    
          <td class="left" colspan="2" nowrap="nowrap" style="background-color:#F0F0F0;"><?php echo $order['payment_city']; ?></td> 
		  <?php } elseif ($filter_report == 'payment_method') { ?>    
          <td class="left" colspan="2" nowrap="nowrap" style="background-color:#F0F0F0;"><?php echo $order['payment_method']; ?></td> 
		  <?php } elseif ($filter_report == 'shipping_method') { ?>
          <td class="left" colspan="2" nowrap="nowrap" style="background-color:#F0F0F0;"><?php echo $order['shipping_method']; ?></td>
		  <?php } ?>   
          <td id="so20_<?php echo $order['order_id']; ?>" class="right" nowrap="nowrap"><?php echo $order['orders']; ?></td>
          <td id="so21_<?php echo $order['order_id']; ?>" class="right" nowrap="nowrap"><?php echo $order['customers']; ?></td>    
          <td id="so22_<?php echo $order['order_id']; ?>" class="right" nowrap="nowrap"><?php echo $order['products']; ?></td>
          <td id="so23_<?php echo $order['order_id']; ?>" class="right" nowrap="nowrap"><?php echo $order['sub_total']; ?></td>
          <td id="so24_<?php echo $order['order_id']; ?>" class="right" nowrap="nowrap"><?php echo $order['handling']; ?></td>
          <td id="so25_<?php echo $order['order_id']; ?>" class="right" nowrap="nowrap"><?php echo $order['low_order_fee']; ?></td>
          <td id="so27_<?php echo $order['order_id']; ?>" class="right" nowrap="nowrap"><?php echo $order['shipping']; ?></td>
          <td id="so26_<?php echo $order['order_id']; ?>" class="right" nowrap="nowrap"><?php echo $order['reward']; ?></td>
          <td id="so28_<?php echo $order['order_id']; ?>" class="right" nowrap="nowrap"><?php echo $order['coupon']; ?></td>          
          <td id="so29_<?php echo $order['order_id']; ?>" class="right" nowrap="nowrap"><?php echo $order['tax']; ?></td>
          <td id="so30_<?php echo $order['order_id']; ?>" class="right" nowrap="nowrap"><?php echo $order['credit']; ?></td>            
          <td id="so31_<?php echo $order['order_id']; ?>" class="right" nowrap="nowrap"><?php echo $order['voucher']; ?></td> 
          <td id="so32_<?php echo $order['order_id']; ?>" class="right" nowrap="nowrap"><?php echo $order['commission']; ?></td>                                
          <td id="so33_<?php echo $order['order_id']; ?>" class="right" nowrap="nowrap"><?php echo $order['total']; ?></td>          
          <?php if ($filter_details == 1 OR $filter_details == 2 OR $filter_details == 3) { ?><td class="right" nowrap="nowrap">[ <a><?php echo $text_detail; ?></a> ]</td><?php } ?> 
          </tr>        
<tr class="detail">
<td colspan="17" class="center">
<?php if ($filter_details == 1) { ?>
<script type="text/javascript">$(function(){ 
$('#show_details_<?php echo $order["order_id"]; ?>').click(function() {
		$('#tab_details_<?php echo $order["order_id"]; ?>').slideToggle('slow');
	});
});
</script>
<div id="tab_details_<?php echo $order['order_id']; ?>" style="display:none">
    <table class="list_detail">
      <thead>
        <tr>
          <td id="so40_<?php echo $order['order_id']; ?>_title" class="left" nowrap="nowrap"><?php echo $column_order_order_id; ?></td>        
          <td id="so41_<?php echo $order['order_id']; ?>_title" class="left" nowrap="nowrap"><?php echo $column_order_date_added; ?></td>
          <td id="so42_<?php echo $order['order_id']; ?>_title" class="left" nowrap="nowrap"><?php echo $column_order_inv_no; ?></td>                  
          <td id="so43_<?php echo $order['order_id']; ?>_title" class="left" nowrap="nowrap"><?php echo $column_order_customer; ?></td>
          <td id="so44_<?php echo $order['order_id']; ?>_title" class="left" nowrap="nowrap"><?php echo $column_order_email; ?></td>
          <td id="so45_<?php echo $order['order_id']; ?>_title" class="left" nowrap="nowrap"><?php echo $column_order_customer_group; ?></td>
          <td id="so46_<?php echo $order['order_id']; ?>_title" class="left" nowrap="nowrap"><?php echo $column_order_shipping_method; ?></td>
          <td id="so47_<?php echo $order['order_id']; ?>_title" class="left" nowrap="nowrap"><?php echo $column_order_payment_method; ?></td>          
          <td id="so48_<?php echo $order['order_id']; ?>_title" class="left" nowrap="nowrap"><?php echo $column_order_status; ?></td>
          <td id="so49_<?php echo $order['order_id']; ?>_title" class="left" nowrap="nowrap"><?php echo $column_order_store; ?></td>
          <td id="so50_<?php echo $order['order_id']; ?>_title" class="right" nowrap="nowrap"><?php echo $column_order_currency; ?></td>
          <td id="so51_<?php echo $order['order_id']; ?>_title" class="right" nowrap="nowrap"><?php echo $column_order_quantity; ?></td>  
          <td id="so52_<?php echo $order['order_id']; ?>_title" class="right" nowrap="nowrap"><?php echo $column_order_sub_total; ?></td>                               
          <td id="so54_<?php echo $order['order_id']; ?>_title" class="right" nowrap="nowrap"><?php echo $column_order_shipping; ?></td>         
          <td id="so55_<?php echo $order['order_id']; ?>_title" class="right" nowrap="nowrap"><?php echo $column_order_tax; ?></td>
          <td id="so56_<?php echo $order['order_id']; ?>_title" class="right" nowrap="nowrap"><?php echo $column_order_value; ?></td>         
        </tr>
      </thead>
        <tr bgcolor="#FFFFFF">
          <td id="so40_<?php echo $order['order_id']; ?>" class="left" nowrap="nowrap"><a><?php echo $order['order_ord_id']; ?></a></td>        
          <td id="so41_<?php echo $order['order_id']; ?>" class="left" nowrap="nowrap"><?php echo $order['order_order_date']; ?></td>
          <td id="so42_<?php echo $order['order_id']; ?>" class="left" nowrap="nowrap"><?php echo $order['order_inv_no']; ?></td>
          <td id="so43_<?php echo $order['order_id']; ?>" class="left" nowrap="nowrap"><?php echo $order['order_name']; ?></td>
          <td id="so44_<?php echo $order['order_id']; ?>" class="left" nowrap="nowrap"><?php echo $order['order_email']; ?></td>
          <td id="so45_<?php echo $order['order_id']; ?>" class="left" nowrap="nowrap"><?php echo $order['order_group']; ?></td>
          <td id="so46_<?php echo $order['order_id']; ?>" class="left" nowrap="nowrap"><?php echo $order['order_shipping_method']; ?></td>
          <td id="so47_<?php echo $order['order_id']; ?>" class="left" nowrap="nowrap"><?php echo $order['order_payment_method']; ?></td>          
          <td id="so48_<?php echo $order['order_id']; ?>" class="left" nowrap="nowrap"><?php echo $order['order_status']; ?></td>
          <td id="so49_<?php echo $order['order_id']; ?>" class="left" nowrap="nowrap"><?php echo $order['order_store']; ?></td> 
          <td id="so50_<?php echo $order['order_id']; ?>" class="right" nowrap="nowrap"><?php echo $order['order_currency']; ?></td>          
          <td id="so51_<?php echo $order['order_id']; ?>" class="right" nowrap="nowrap"><?php echo $order['order_products']; ?></td> 
          <td id="so52_<?php echo $order['order_id']; ?>" class="right" nowrap="nowrap"><?php echo $order['order_sub_total']; ?></td>                    
          <td id="so54_<?php echo $order['order_id']; ?>" class="right" nowrap="nowrap"><?php echo $order['order_shipping']; ?></td>           
          <td id="so55_<?php echo $order['order_id']; ?>" class="right" nowrap="nowrap"><?php echo $order['order_tax']; ?></td>                              
          <td id="so56_<?php echo $order['order_id']; ?>" class="right" nowrap="nowrap"><?php echo $order['order_value']; ?></td>   
         </tr>
    </table>  
</div>
<?php } ?>    
<?php if ($filter_details == 2) { ?>
<script type="text/javascript">$(function(){ 
$('#show_details_<?php echo $order["order_id"]; ?>').click(function() {
		$('#tab_details_<?php echo $order["order_id"]; ?>').slideToggle('slow');
	});
});
</script>
<div id="tab_details_<?php echo $order['order_id']; ?>" style="display:none">
    <table class="list_detail">
      <thead>
        <tr>
          <td id="so60_<?php echo $order['order_id']; ?>_title" class="left" nowrap="nowrap"><?php echo $column_prod_order_id; ?></td>  
          <td id="so61_<?php echo $order['order_id']; ?>_title" class="left" nowrap="nowrap"><?php echo $column_prod_date_added; ?></td>
          <td id="so62_<?php echo $order['order_id']; ?>_title" class="left" nowrap="nowrap"><?php echo $column_prod_inv_no; ?></td> 
          <td id="so63_<?php echo $order['order_id']; ?>_title" class="left" nowrap="nowrap"><?php echo $column_prod_id; ?></td>                                          
          <td id="so64_<?php echo $order['order_id']; ?>_title" class="left" nowrap="nowrap"><?php echo $column_prod_sku; ?></td>
          <td id="so65_<?php echo $order['order_id']; ?>_title" class="left" nowrap="nowrap"><?php echo $column_prod_model; ?></td>            
          <td id="so66_<?php echo $order['order_id']; ?>_title" class="left" nowrap="nowrap"><?php echo $column_prod_name; ?></td> 
          <td id="so67_<?php echo $order['order_id']; ?>_title" class="left" nowrap="nowrap"><?php echo $column_prod_option; ?></td>           
          <td id="so77_<?php echo $order['order_id']; ?>_title" class="left" nowrap="nowrap"><?php echo $column_prod_attributes; ?></td>                      
          <td id="so68_<?php echo $order['order_id']; ?>_title" class="left" nowrap="nowrap"><?php echo $column_prod_manu; ?></td> 
          <td id="so79_<?php echo $order['order_id']; ?>_title" class="left" nowrap="nowrap"><?php echo $column_prod_category; ?></td>           
          <td id="so69_<?php echo $order['order_id']; ?>_title" class="right" nowrap="nowrap"><?php echo $column_prod_currency; ?></td>   
          <td id="so70_<?php echo $order['order_id']; ?>_title" class="right" nowrap="nowrap"><?php echo $column_prod_price; ?></td>                     
          <td id="so71_<?php echo $order['order_id']; ?>_title" class="right" nowrap="nowrap"><?php echo $column_prod_quantity; ?></td> 
          <td id="so72a_<?php echo $order['order_id']; ?>_title" class="right" nowrap="nowrap"><?php echo $column_prod_total_excl_vat; ?></td>                    
          <td id="so73_<?php echo $order['order_id']; ?>_title" class="right" nowrap="nowrap"><?php echo $column_prod_tax; ?></td>                   
          <td id="so72b_<?php echo $order['order_id']; ?>_title" class="right" nowrap="nowrap"><?php echo $column_prod_total_incl_vat; ?></td> 
        </tr>
      </thead>
        <tr bgcolor="#FFFFFF">
          <td id="so60_<?php echo $order['order_id']; ?>" class="left" nowrap="nowrap"><a><?php echo $order['product_ord_id']; ?></a></td>  
          <td id="so61_<?php echo $order['order_id']; ?>" class="left" nowrap="nowrap"><?php echo $order['product_order_date']; ?></td>
          <td id="so62_<?php echo $order['order_id']; ?>" class="left" nowrap="nowrap"><?php echo $order['product_inv_no']; ?></td>
          <td id="so63_<?php echo $order['order_id']; ?>" class="left" nowrap="nowrap"><?php echo $order['product_pid']; ?></td>  
          <td id="so64_<?php echo $order['order_id']; ?>" class="left" nowrap="nowrap"><?php echo $order['product_sku']; ?></td>
          <td id="so65_<?php echo $order['order_id']; ?>" class="left" nowrap="nowrap"><?php echo $order['product_model']; ?></td>                 
          <td id="so66_<?php echo $order['order_id']; ?>" class="left" nowrap="nowrap"><?php echo $order['product_name']; ?></td> 
          <td id="so67_<?php echo $order['order_id']; ?>" class="left" nowrap="nowrap"><?php echo $order['product_option']; ?></td>            
          <td id="so77_<?php echo $order['order_id']; ?>" class="left" nowrap="nowrap"><?php echo $order['product_attributes']; ?></td>                    
          <td id="so68_<?php echo $order['order_id']; ?>" class="left" nowrap="nowrap"><?php echo $order['product_manu']; ?></td> 
          <td id="so79_<?php echo $order['order_id']; ?>" class="left" nowrap="nowrap"><?php echo $order['product_category']; ?></td>           
          <td id="so69_<?php echo $order['order_id']; ?>" class="right" nowrap="nowrap"><?php echo $order['product_currency']; ?></td> 
          <td id="so70_<?php echo $order['order_id']; ?>" class="right" nowrap="nowrap"><?php echo $order['product_price']; ?></td> 
          <td id="so71_<?php echo $order['order_id']; ?>" class="right" nowrap="nowrap"><?php echo $order['product_quantity']; ?></td> 
          <td id="so72a_<?php echo $order['order_id']; ?>" class="right" nowrap="nowrap"><?php echo $order['product_total_excl_vat']; ?></td>  
          <td id="so73_<?php echo $order['order_id']; ?>" class="right" nowrap="nowrap"><?php echo $order['product_tax']; ?></td>  
          <td id="so72b_<?php echo $order['order_id']; ?>" class="right" nowrap="nowrap"><?php echo $order['product_total_incl_vat']; ?></td> 
         </tr>       
    </table>
</div> 
<?php } ?>  
<?php if ($filter_details == 3) { ?>
<script type="text/javascript">$(function(){ 
$('#show_details_<?php echo $order["order_id"]; ?>').click(function() {
		$('#tab_details_<?php echo $order["order_id"]; ?>').slideToggle('slow');
	});
});
</script>
<div id="tab_details_<?php echo $order['order_id']; ?>" style="display:none">
    <table class="list_detail">
      <thead>
        <tr>
          <td id="so80_<?php echo $order['order_id']; ?>_title" class="left" nowrap="nowrap"><?php echo $column_customer_order_id; ?></td>        
          <td id="so81_<?php echo $order['order_id']; ?>_title" class="left" nowrap="nowrap"><?php echo $column_customer_date_added; ?></td>
          <td id="so82_<?php echo $order['order_id']; ?>_title" class="left" nowrap="nowrap"><?php echo $column_customer_inv_no; ?></td>           
          <td id="so83_<?php echo $order['order_id']; ?>_title" class="left" nowrap="nowrap"><?php echo $column_customer_cust_id; ?></td>           
          <td id="so84_<?php echo $order['order_id']; ?>_title" class="left" nowrap="nowrap"><?php echo $column_billing_name; ?></td> 
          <td id="so85_<?php echo $order['order_id']; ?>_title" class="left" nowrap="nowrap"><?php echo $column_billing_company; ?></td> 
          <td id="so86_<?php echo $order['order_id']; ?>_title" class="left" nowrap="nowrap"><?php echo $column_billing_address_1; ?></td> 
          <td id="so87_<?php echo $order['order_id']; ?>_title" class="left" nowrap="nowrap"><?php echo $column_billing_address_2; ?></td> 
          <td id="so88_<?php echo $order['order_id']; ?>_title" class="left" nowrap="nowrap"><?php echo $column_billing_city; ?></td>
          <td id="so89_<?php echo $order['order_id']; ?>_title" class="left" nowrap="nowrap"><?php echo $column_billing_zone; ?></td> 
          <td id="so90_<?php echo $order['order_id']; ?>_title" class="left" nowrap="nowrap"><?php echo $column_billing_postcode; ?></td>
          <td id="so91_<?php echo $order['order_id']; ?>_title" class="left" nowrap="nowrap"><?php echo $column_billing_country; ?></td>
          <td id="so92_<?php echo $order['order_id']; ?>_title" class="left" nowrap="nowrap"><?php echo $column_customer_telephone; ?></td>
          <td id="so93_<?php echo $order['order_id']; ?>_title" class="left" nowrap="nowrap"><?php echo $column_shipping_name; ?></td> 
          <td id="so94_<?php echo $order['order_id']; ?>_title" class="left" nowrap="nowrap"><?php echo $column_shipping_company; ?></td> 
          <td id="so95_<?php echo $order['order_id']; ?>_title" class="left" nowrap="nowrap"><?php echo $column_shipping_address_1; ?></td> 
          <td id="so96_<?php echo $order['order_id']; ?>_title" class="left" nowrap="nowrap"><?php echo $column_shipping_address_2; ?></td> 
          <td id="so97_<?php echo $order['order_id']; ?>_title" class="left" nowrap="nowrap"><?php echo $column_shipping_city; ?></td>
          <td id="so98_<?php echo $order['order_id']; ?>_title" class="left" nowrap="nowrap"><?php echo $column_shipping_zone; ?></td> 
          <td id="so99_<?php echo $order['order_id']; ?>_title" class="left" nowrap="nowrap"><?php echo $column_shipping_postcode; ?></td>
          <td id="so100_<?php echo $order['order_id']; ?>_title" class="left" nowrap="nowrap"><?php echo $column_shipping_country; ?></td>          
        </tr>
      </thead>
        <tr bgcolor="#FFFFFF">
          <td id="so80_<?php echo $order['order_id']; ?>" class="left" nowrap="nowrap"><?php echo $order['customer_ord_id']; ?></td>        
          <td id="so81_<?php echo $order['order_id']; ?>" class="left" nowrap="nowrap"><?php echo $order['customer_order_date']; ?></td>
          <td id="so82_<?php echo $order['order_id']; ?>" class="left" nowrap="nowrap"><?php echo $order['customer_inv_no']; ?></td>
          <td id="so83_<?php echo $order['order_id']; ?>" class="left" nowrap="nowrap"><?php echo $order['customer_cust_id']; ?></td>             
          <td id="so84_<?php echo $order['order_id']; ?>" class="left" nowrap="nowrap"><?php echo $order['billing_name']; ?></td>         
          <td id="so85_<?php echo $order['order_id']; ?>" class="left" nowrap="nowrap"><?php echo $order['billing_company']; ?></td> 
          <td id="so86_<?php echo $order['order_id']; ?>" class="left" nowrap="nowrap"><?php echo $order['billing_address_1']; ?></td> 
          <td id="so87_<?php echo $order['order_id']; ?>" class="left" nowrap="nowrap"><?php echo $order['billing_address_2']; ?></td> 
          <td id="so88_<?php echo $order['order_id']; ?>" class="left" nowrap="nowrap"><?php echo $order['billing_city']; ?></td> 
          <td id="so89_<?php echo $order['order_id']; ?>" class="left" nowrap="nowrap"><?php echo $order['billing_zone']; ?></td> 
          <td id="so90_<?php echo $order['order_id']; ?>" class="left" nowrap="nowrap"><?php echo $order['billing_postcode']; ?></td>                    
          <td id="so91_<?php echo $order['order_id']; ?>" class="left" nowrap="nowrap"><?php echo $order['billing_country']; ?></td>
          <td id="so92_<?php echo $order['order_id']; ?>" class="left" nowrap="nowrap"><?php echo $order['customer_telephone']; ?></td> 
          <td id="so93_<?php echo $order['order_id']; ?>" class="left" nowrap="nowrap"><?php echo $order['shipping_name']; ?></td>         
          <td id="so94_<?php echo $order['order_id']; ?>" class="left" nowrap="nowrap"><?php echo $order['shipping_company']; ?></td> 
          <td id="so95_<?php echo $order['order_id']; ?>" class="left" nowrap="nowrap"><?php echo $order['shipping_address_1']; ?></td> 
          <td id="so96_<?php echo $order['order_id']; ?>" class="left" nowrap="nowrap"><?php echo $order['shipping_address_2']; ?></td> 
          <td id="so97_<?php echo $order['order_id']; ?>" class="left" nowrap="nowrap"><?php echo $order['shipping_city']; ?></td> 
          <td id="so98_<?php echo $order['order_id']; ?>" class="left" nowrap="nowrap"><?php echo $order['shipping_zone']; ?></td> 
          <td id="so99_<?php echo $order['order_id']; ?>" class="left" nowrap="nowrap"><?php echo $order['shipping_postcode']; ?></td>                    
          <td id="so100_<?php echo $order['order_id']; ?>" class="left" nowrap="nowrap"><?php echo $order['shipping_country']; ?></td>          
         </tr>
    </table>
</div> 
<?php } ?>
</td>
</tr>          
        <?php } ?>
        <tr>
        <td colspan="17"></td>
        </tr>       
        <tr>
          <td colspan="2" class="right" style="background-color:#E7EFEF;"><strong><?php echo $text_filter_total; ?></strong></td>
          <td id="so20_total" class="right" nowrap="nowrap" style="background-color:#E7EFEF; color:#003A88;"><strong><?php echo $order['orders_total']; ?></strong></td>
          <td id="so21_total" class="right" nowrap="nowrap" style="background-color:#E7EFEF; color:#003A88;"><strong><?php echo $order['customers_total']; ?></strong></td>
          <td id="so22_total" class="right" nowrap="nowrap" style="background-color:#E7EFEF; color:#003A88;"><strong><?php echo $order['products_total']; ?></strong></td>
          <td id="so23_total" class="right" nowrap="nowrap" style="background-color:#E7EFEF; color:#003A88;"><strong><?php echo $order['sub_total_total']; ?></strong></td>
          <td id="so24_total" class="right" nowrap="nowrap" style="background-color:#E7EFEF; color:#003A88;"><strong><?php echo $order['handling_total']; ?></strong></td>
          <td id="so25_total" class="right" nowrap="nowrap" style="background-color:#E7EFEF; color:#003A88;"><strong><?php echo $order['low_order_fee_total']; ?></strong></td>
          <td id="so27_total" class="right" nowrap="nowrap" style="background-color:#E7EFEF; color:#003A88;"><strong><?php echo $order['shipping_total']; ?></strong></td>
          <td id="so26_total" class="right" nowrap="nowrap" style="background-color:#E7EFEF; color:#003A88;"><strong><?php echo $order['reward_total']; ?></strong></td>
          <td id="so28_total" class="right" nowrap="nowrap" style="background-color:#E7EFEF; color:#003A88;"><strong><?php echo $order['coupon_total']; ?></strong></td>
          <td id="so29_total" class="right" nowrap="nowrap" style="background-color:#E7EFEF; color:#003A88;"><strong><?php echo $order['tax_total']; ?></strong></td>
          <td id="so30_total" class="right" nowrap="nowrap" style="background-color:#E7EFEF; color:#003A88;"><strong><?php echo $order['credit_total']; ?></strong></td>
          <td id="so31_total" class="right" nowrap="nowrap" style="background-color:#E7EFEF; color:#003A88;"><strong><?php echo $order['voucher_total']; ?></strong></td>
          <td id="so32_total" class="right" nowrap="nowrap" style="background-color:#E7EFEF; color:#003A88;"><strong><?php echo $order['commission_total']; ?></strong></td>
          <td id="so33_total" class="right" nowrap="nowrap" style="background-color:#E7EFEF; color:#003A88;"><strong><?php echo $order['total_total']; ?></strong></td>
          <?php if ($filter_details == 1 OR $filter_details == 2 OR $filter_details == 3) { ?><td></td><?php } ?>                  
        </tr>           
          <?php } else { ?>
          <tr>
          <td class="noresult" colspan="17"><?php echo $text_no_results; ?></td>
          </tr>
          <?php } ?>  
          </tbody>                     
      </table>
<?php } ?>
    </div>
      <?php if ($orders) { ?>    
      <div class="pagination_report"></div>
      <?php } ?>       
    </div>
    </div>    
  </div>
</div>  
</form>
<script type="text/javascript" src="view/javascript/jquery/jquery.multiselect.js"></script>
<script type="text/javascript" src="view/javascript/jquery/jquery.paging.min.js"></script>
<script type="text/javascript" src="view/javascript/jquery/vtip.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	$('#date-start').datepicker({changeMonth: true, changeYear: true, dateFormat: 'yy-mm-dd'});
	$('#date-end').datepicker({changeMonth: true, changeYear: true, dateFormat: 'yy-mm-dd'});

	$('#status-date-start').datepicker({changeMonth: true, changeYear: true, dateFormat: 'yy-mm-dd'});
	$('#status-date-end').datepicker({changeMonth: true, changeYear: true, dateFormat: 'yy-mm-dd'});
	
    $('#filter_order_status_id').multiSelect({
      selectAll:'true', selectAllText:'<?php echo $text_all; ?>', noneSelected:'<?php echo $text_all_status; ?>', oneOrMoreSelected:'<?php echo $text_selected; ?>'
      });
	
    $('#filter_store_id').multiSelect({
      selectAll:'true', selectAllText:'<?php echo $text_all; ?>', noneSelected:'<?php echo $text_all_stores; ?>', oneOrMoreSelected:'<?php echo $text_selected; ?>'
      });
	
    $('#filter_currency').multiSelect({
      selectAll:'true', selectAllText:'<?php echo $text_all; ?>', noneSelected:'<?php echo $text_all_currencies; ?>', oneOrMoreSelected:'<?php echo $text_selected; ?>'
      });

    $('#filter_taxes').multiSelect({
      selectAll:'true', selectAllText:'<?php echo $text_all; ?>', noneSelected:'<?php echo $text_all_taxes; ?>', oneOrMoreSelected:'<?php echo $text_selected; ?>'
      });

    $('#filter_tax_classes').multiSelect({
      selectAll:'true', selectAllText:'<?php echo $text_all; ?>', noneSelected:'<?php echo $text_all_tax_classes; ?>', oneOrMoreSelected:'<?php echo $text_selected; ?>'
      });
	
    $('#filter_geo_zones').multiSelect({
      selectAll:'true', selectAllText:'<?php echo $text_all; ?>', noneSelected:'<?php echo $text_all_zones; ?>', oneOrMoreSelected:'<?php echo $text_selected; ?>'
      });
	
    $('#filter_customer_group_id').multiSelect({
      selectAll:'true', selectAllText:'<?php echo $text_all; ?>', noneSelected:'<?php echo $text_all_groups; ?>', oneOrMoreSelected:'<?php echo $text_selected; ?>'
      });

    $('#filter_payment_method').multiSelect({
      selectAll:'true', selectAllText:'<?php echo $text_all; ?>', noneSelected:'<?php echo $text_all_payment_methods; ?>', oneOrMoreSelected:'<?php echo $text_selected; ?>'
      });

    $('#filter_shipping_method').multiSelect({
      selectAll:'true', selectAllText:'<?php echo $text_all; ?>', noneSelected:'<?php echo $text_all_shipping_methods; ?>', oneOrMoreSelected:'<?php echo $text_selected; ?>'
      });

    $('#filter_category').multiSelect({
      selectAll:'true', selectAllText:'<?php echo $text_all; ?>', noneSelected:'<?php echo $text_all_categories; ?>', oneOrMoreSelected:'<?php echo $text_selected; ?>'
      });

    $('#filter_manufacturer').multiSelect({
      selectAll:'true', selectAllText:'<?php echo $text_all; ?>', noneSelected:'<?php echo $text_all_manufacturers; ?>', oneOrMoreSelected:'<?php echo $text_selected; ?>'
      });
	
    $('#filter_option').multiSelect({
      selectAll:'true', selectAllText:'<?php echo $text_all; ?>', noneSelected:'<?php echo $text_all_options; ?>', oneOrMoreSelected:'<?php echo $text_selected; ?>'
      });

    $('#filter_attribute').multiSelect({
      selectAll:'true', selectAllText:'<?php echo $text_all; ?>', noneSelected:'<?php echo $text_all_attributes; ?>', oneOrMoreSelected:'<?php echo $text_selected; ?>'
      });
	
    $('#filter_location').multiSelect({
      selectAll:'true', selectAllText:'<?php echo $text_all; ?>', noneSelected:'<?php echo $text_all_locations; ?>', oneOrMoreSelected:'<?php echo $text_selected; ?>'
      });

    $('#filter_affiliate_name').multiSelect({
      selectAll:'true', selectAllText:'<?php echo $text_all; ?>', noneSelected:'<?php echo $text_all_affiliate_names; ?>', oneOrMoreSelected:'<?php echo $text_selected; ?>'
      });

    $('#filter_affiliate_email').multiSelect({
      selectAll:'true', selectAllText:'<?php echo $text_all; ?>', noneSelected:'<?php echo $text_all_affiliate_emails; ?>', oneOrMoreSelected:'<?php echo $text_selected; ?>'
      });
	
    $('#filter_coupon_name').multiSelect({
      selectAll:'true', selectAllText:'<?php echo $text_all; ?>', noneSelected:'<?php echo $text_all_coupon_names; ?>', oneOrMoreSelected:'<?php echo $text_selected; ?>'
      });
	
    $('#export_xls').click(function() {
      $('#export').val('1') ; // export_xls: #1
      $('#report').attr('target', '_blank'); // opening file in a new window
      $('#report').submit() ;
      $('#report').attr('target', '_self'); // preserve current form      
      $('#export').val('') ; 
      return(false)
    });	
	
    $('#export_xls_order_list').click(function() {
      $('#export').val('2') ; // export_xls_order_list: #2
      $('#report').attr('target', '_blank'); // opening file in a new window
      $('#report').submit() ;
      $('#report').attr('target', '_self'); // preserve current form      
      $('#export').val('') ; 
      return(false)
    });	

    $('#export_xls_product_list').click(function() {
      $('#export').val('3') ; // export_xls_product_list: #3
      $('#report').attr('target', '_blank'); // opening file in a new window
      $('#report').submit() ;
      $('#report').attr('target', '_self'); // preserve current form      
      $('#export').val('') ; 
      return(false)
    });	

    $('#export_xls_customer_list').click(function() {
      $('#export').val('4') ; // export_xls_customer_list: #4
      $('#report').attr('target', '_blank'); // opening file in a new window
      $('#report').submit() ;
      $('#report').attr('target', '_self'); // preserve current form      
      $('#export').val('') ; 
      return(false)
    });	
	
    $('#export_xls_all_details').click(function() {
      $('#export').val('5') ; // export_xls_all_details: #5
      $('#report').attr('target', '_blank'); // opening file in a new window
      $('#report').submit() ;
      $('#report').attr('target', '_self'); // preserve current form      
      $('#export').val('') ; 
      return(false)
    });	
	
    $('#export_html').click(function() {
      $('#export').val('6') ; // export_html: #6
      $('#report').attr('target', '_blank'); // opening file in a new window
      $('#report').submit() ;
      $('#report').attr('target', '_self'); // preserve current form      
      $('#export').val('') ; 
      return(false)
    });	
	
    $('#export_html_order_list').click(function() {
      $('#export').val('7') ; // export_html_order_list: #7
      $('#report').attr('target', '_blank'); // opening file in a new window
      $('#report').submit() ;
      $('#report').attr('target', '_self'); // preserve current form      
      $('#export').val('') ; 
      return(false)
    });	
	
    $('#export_html_product_list').click(function() {
      $('#export').val('8') ; // export_html_product_list: #8
      $('#report').attr('target', '_blank'); // opening file in a new window
      $('#report').submit() ;
      $('#report').attr('target', '_self'); // preserve current form      
      $('#export').val('') ; 
      return(false)
    });		
	
    $('#export_html_customer_list').click(function() {
      $('#export').val('9') ; // export_html_customer_list: #9
      $('#report').attr('target', '_blank'); // opening file in a new window
      $('#report').submit() ;
      $('#report').attr('target', '_self'); // preserve current form      
      $('#export').val('') ; 
      return(false)
    });	
	
    $('#export_html_all_details').click(function() {
      $('#export').val('10') ; // export_html_all_details: #10
      $('#report').attr('target', '_blank'); // opening file in a new window
      $('#report').submit() ;
      $('#report').attr('target', '_self'); // preserve current form      
      $('#export').val('') ; 
      return(false)
    });	
	
    $('#export_pdf').click(function() {
      $('#export').val('11') ; // export_pdf: #11
      $('#report').attr('target', '_blank'); // opening file in a new window
      $('#report').submit() ;
      $('#report').attr('target', '_self'); // preserve current form      
      $('#export').val('') ; 
      return(false)
    });	
	
    $('#export_pdf_order_list').click(function() {
      $('#export').val('12') ; // export_pdf_order_list: #12
      $('#report').attr('target', '_blank'); // opening file in a new window
      $('#report').submit() ;
      $('#report').attr('target', '_self'); // preserve current form      
      $('#export').val('') ; 
      return(false)
    });	
	
    $('#export_pdf_product_list').click(function() {
      $('#export').val('13') ; // export_pdf_product_list: #13
      $('#report').attr('target', '_blank'); // opening file in a new window
      $('#report').submit() ;
      $('#report').attr('target', '_self'); // preserve current form      
      $('#export').val('') ; 
      return(false)
    });		
	
    $('#export_pdf_customer_list').click(function() {
      $('#export').val('14') ; // export_pdf_customer_list: #14
      $('#report').attr('target', '_blank'); // opening file in a new window
      $('#report').submit() ;
      $('#report').attr('target', '_self'); // preserve current form      
      $('#export').val('') ; 
      return(false)
    });	
	
    $('#export_pdf_all_details').click(function() {
      $('#export').val('15') ; // export_pdf_all_details: #15
      $('#report').attr('target', '_blank'); // opening file in a new window
      $('#report').submit() ;
      $('#report').attr('target', '_self'); // preserve current form      
      $('#export').val('') ; 
      return(false)
    });	
	
    $('#export_csv').click(function() {
      $('#export').val('16') ; // export_csv: #16
      $('#report').attr('target', '_blank'); // opening file in a new window
      $('#report').submit() ;
      $('#report').attr('target', '_self'); // preserve current form      
      $('#export').val('') ; 
      return(false)
    });
	
    $('#export_csv_all_details').click(function() {
      $('#export').val('17') ; // export_csv_all_details: #17
      $('#report').attr('target', '_blank'); // opening file in a new window
      $('#report').submit() ;
      $('#report').attr('target', '_self'); // preserve current form      
      $('#export').val('') ; 
      return(false)
    });	
	
    $('#export_xlsx').click(function() {
      $('#export').val('18') ; // export_xlsx: #18
      $('#report').attr('target', '_blank'); // opening file in a new window
      $('#report').submit() ;
      $('#report').attr('target', '_self'); // preserve current form      
      $('#export').val('') ; 
      return(false)
    });
	
    $('#export_xlsx_all_details').click(function() {
      $('#export').val('19') ; // export_xlsx_all_details: #19
      $('#report').attr('target', '_blank'); // opening file in a new window
      $('#report').submit() ;
      $('#report').attr('target', '_self'); // preserve current form      
      $('#export').val('') ; 
      return(false)
    });		
});
</script>  
<script type="text/javascript"><!--
$('input[name=\'filter_customer_name\']').autocomplete({
	delay: 0,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=report/adv_sale_order/customer_autocomplete&token=<?php echo $token; ?>&filter_customer_name=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {		
				response($.map(json, function(item) {
					return {
						label: item.cust_name,
						value: item.customer_id
					}
				}));
			}
		});
	}, 
	select: function(event, ui) {
		$('input[name=\'filter_customer_name\']').val(ui.item.label);
						
		return false;
	}
});

$('input[name=\'filter_customer_email\']').autocomplete({
	delay: 0,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=report/adv_sale_order/customer_autocomplete&token=<?php echo $token; ?>&filter_customer_email=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {		
				response($.map(json, function(item) {
					return {
						label: item.cust_email,
						value: item.customer_id
					}
				}));
			}
		});
	}, 
	select: function(event, ui) {
		$('input[name=\'filter_customer_email\']').val(ui.item.label);
						
		return false;
	}
});

$('input[name=\'filter_customer_telephone\']').autocomplete({
	delay: 0,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=report/adv_sale_order/customer_autocomplete&token=<?php echo $token; ?>&filter_customer_telephone=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {		
				response($.map(json, function(item) {
					return {
						label: item.cust_telephone,
						value: item.customer_id
					}
				}));
			}
		});
	}, 
	select: function(event, ui) {
		$('input[name=\'filter_customer_telephone\']').val(ui.item.label);
						
		return false;
	}
});

$('input[name=\'filter_ip\']').autocomplete({
	delay: 0,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=report/adv_sale_order/customer_autocomplete&token=<?php echo $token; ?>&filter_ip=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {		
				response($.map(json, function(item) {
					return {
						label: item.cust_ip,
						value: item.customer_id
					}
				}));
			}
		});
	}, 
	select: function(event, ui) {
		$('input[name=\'filter_ip\']').val(ui.item.label);
						
		return false;
	}
});

$('input[name=\'filter_payment_company\']').autocomplete({
	delay: 0,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=report/adv_sale_order/customer_autocomplete&token=<?php echo $token; ?>&filter_payment_company=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {		
				response($.map(json, function(item) {
					return {
						label: item.payment_company,
						value: item.customer_id
					}
				}));
			}
		});
	}, 
	select: function(event, ui) {
		$('input[name=\'filter_payment_company\']').val(ui.item.label);
						
		return false;
	}
});

$('input[name=\'filter_payment_address\']').autocomplete({
	delay: 0,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=report/adv_sale_order/customer_autocomplete&token=<?php echo $token; ?>&filter_payment_address=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {		
				response($.map(json, function(item) {
					return {
						label: item.payment_address,
						value: item.customer_id
					}
				}));
			}
		});
	}, 
	select: function(event, ui) {
		$('input[name=\'filter_payment_address\']').val(ui.item.label);
						
		return false;
	}
});

$('input[name=\'filter_payment_city\']').autocomplete({
	delay: 0,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=report/adv_sale_order/customer_autocomplete&token=<?php echo $token; ?>&filter_payment_city=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {		
				response($.map(json, function(item) {
					return {
						label: item.payment_city,
						value: item.customer_id
					}
				}));
			}
		});
	}, 
	select: function(event, ui) {
		$('input[name=\'filter_payment_city\']').val(ui.item.label);
						
		return false;
	}
});

$('input[name=\'filter_payment_zone\']').autocomplete({
	delay: 0,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=report/adv_sale_order/customer_autocomplete&token=<?php echo $token; ?>&filter_payment_zone=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {		
				response($.map(json, function(item) {
					return {
						label: item.payment_zone,
						value: item.customer_id
					}
				}));
			}
		});
	}, 
	select: function(event, ui) {
		$('input[name=\'filter_payment_zone\']').val(ui.item.label);
						
		return false;
	}
});

$('input[name=\'filter_payment_postcode\']').autocomplete({
	delay: 0,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=report/adv_sale_order/customer_autocomplete&token=<?php echo $token; ?>&filter_payment_postcode=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {		
				response($.map(json, function(item) {
					return {
						label: item.payment_postcode,
						value: item.customer_id
					}
				}));
			}
		});
	}, 
	select: function(event, ui) {
		$('input[name=\'filter_payment_postcode\']').val(ui.item.label);
						
		return false;
	}
});

$('input[name=\'filter_payment_country\']').autocomplete({
	delay: 0,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=report/adv_sale_order/customer_autocomplete&token=<?php echo $token; ?>&filter_payment_country=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {		
				response($.map(json, function(item) {
					return {
						label: item.payment_country,
						value: item.customer_id
					}
				}));
			}
		});
	}, 
	select: function(event, ui) {
		$('input[name=\'filter_payment_country\']').val(ui.item.label);
						
		return false;
	}
});

$('input[name=\'filter_shipping_company\']').autocomplete({
	delay: 0,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=report/adv_sale_order/customer_autocomplete&token=<?php echo $token; ?>&filter_shipping_company=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {		
				response($.map(json, function(item) {
					return {
						label: item.shipping_company,
						value: item.customer_id
					}
				}));
			}
		});
	}, 
	select: function(event, ui) {
		$('input[name=\'filter_shipping_company\']').val(ui.item.label);
						
		return false;
	}
});

$('input[name=\'filter_shipping_address\']').autocomplete({
	delay: 0,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=report/adv_sale_order/customer_autocomplete&token=<?php echo $token; ?>&filter_shipping_address=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {		
				response($.map(json, function(item) {
					return {
						label: item.shipping_address,
						value: item.customer_id
					}
				}));
			}
		});
	}, 
	select: function(event, ui) {
		$('input[name=\'filter_shipping_address\']').val(ui.item.label);
						
		return false;
	}
});

$('input[name=\'filter_shipping_city\']').autocomplete({
	delay: 0,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=report/adv_sale_order/customer_autocomplete&token=<?php echo $token; ?>&filter_shipping_city=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {		
				response($.map(json, function(item) {
					return {
						label: item.shipping_city,
						value: item.customer_id
					}
				}));
			}
		});
	}, 
	select: function(event, ui) {
		$('input[name=\'filter_shipping_city\']').val(ui.item.label);
						
		return false;
	}
});

$('input[name=\'filter_shipping_zone\']').autocomplete({
	delay: 0,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=report/adv_sale_order/customer_autocomplete&token=<?php echo $token; ?>&filter_shipping_zone=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {		
				response($.map(json, function(item) {
					return {
						label: item.shipping_zone,
						value: item.customer_id
					}
				}));
			}
		});
	}, 
	select: function(event, ui) {
		$('input[name=\'filter_shipping_zone\']').val(ui.item.label);
						
		return false;
	}
});

$('input[name=\'filter_shipping_postcode\']').autocomplete({
	delay: 0,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=report/adv_sale_order/customer_autocomplete&token=<?php echo $token; ?>&filter_shipping_postcode=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {		
				response($.map(json, function(item) {
					return {
						label: item.shipping_postcode,
						value: item.customer_id
					}
				}));
			}
		});
	}, 
	select: function(event, ui) {
		$('input[name=\'filter_shipping_postcode\']').val(ui.item.label);
						
		return false;
	}
});

$('input[name=\'filter_shipping_country\']').autocomplete({
	delay: 0,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=report/adv_sale_order/customer_autocomplete&token=<?php echo $token; ?>&filter_shipping_country=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {		
				response($.map(json, function(item) {
					return {
						label: item.shipping_country,
						value: item.customer_id
					}
				}));
			}
		});
	}, 
	select: function(event, ui) {
		$('input[name=\'filter_shipping_country\']').val(ui.item.label);
						
		return false;
	}
});

$('input[name=\'filter_sku\']').autocomplete({
	delay: 0,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=report/adv_sale_order/product_autocomplete&token=<?php echo $token; ?>&filter_sku=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {		
				response($.map(json, function(item) {
					return {
						label: item.prod_sku,
						value: item.product_id
					}
				}));
			}
		});
	}, 
	select: function(event, ui) {
		$('input[name=\'filter_sku\']').val(ui.item.label);
						
		return false;
	}
});

$('input[name=\'filter_product_id\']').autocomplete({
	delay: 0,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=report/adv_sale_order/product_autocomplete&token=<?php echo $token; ?>&filter_product_id=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {		
				response($.map(json, function(item) {
					return {
						label: item.prod_name,
						value: item.product_id
					}
				}));
			}
		});
	}, 
	select: function(event, ui) {
		$('input[name=\'filter_product_id\']').val(ui.item.label);
						
		return false;
	}
});

$('input[name=\'filter_model\']').autocomplete({
	delay: 0,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=report/adv_sale_order/product_autocomplete&token=<?php echo $token; ?>&filter_model=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {		
				response($.map(json, function(item) {
					return {
						label: item.prod_model,
						value: item.product_id
					}
				}));
			}
		});
	}, 
	select: function(event, ui) {
		$('input[name=\'filter_model\']').val(ui.item.label);
						
		return false;
	}
});

$('input[name=\'filter_coupon_code\']').autocomplete({
	delay: 0,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=report/adv_sale_order/coupon_autocomplete&token=<?php echo $token; ?>&filter_coupon_code=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {		
				response($.map(json, function(item) {
					return {
						label: item.coupon_code,
						value: item.coupon_id
					}
				}));
			}
		});
	}, 
	select: function(event, ui) {
		$('input[name=\'filter_coupon_code\']').val(ui.item.label);
						
		return false;
	}
});

$('input[name=\'filter_voucher_code\']').autocomplete({
	delay: 0,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=report/adv_sale_order/voucher_autocomplete&token=<?php echo $token; ?>&filter_voucher_code=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {		
				response($.map(json, function(item) {
					return {
						label: item.voucher_code,
						value: item.voucher_id
					}
				}));
			}
		});
	}, 
	select: function(event, ui) {
		$('input[name=\'filter_voucher_code\']').val(ui.item.label);
						
		return false;
	}
});
//--></script> 
<?php if ($orders) { ?> 
	<?php if ($filter_report == 'sales_summary') { ?> 
	<?php if ($filter_details != '4' && ($filter_group == 'year' or $filter_group == 'quarter' or $filter_group == 'month')) { ?>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript"><!--
	google.load('visualization', '1', {packages: ['corechart']});
      google.setOnLoadCallback(drawChart);      
	  function drawChart() {        
	  	var data = google.visualization.arrayToDataTable([
			<?php if ($orders && $filter_group == 'month') {
				echo "['" . $column_month . "','". $column_orders . "','" . $column_customers . "','" . $column_products . "'],";
					foreach ($orders as $key => $order) {
						if (count($orders)==($key+1)) {
							echo "['" . $order['year_month'] . "',". $order['orders'] . ",". $order['customers'] . ",". $order['products'] . "]";
						} else {
							echo "['" . $order['year_month'] . "',". $order['orders'] . ",". $order['customers'] . ",". $order['products'] . "],";
						}
					}	
			} elseif ($orders && $filter_group == 'quarter') {
				echo "['" . $column_quarter . "','". $column_orders . "','" . $column_customers . "','" . $column_products . "'],";
					foreach ($orders as $key => $order) {
						if (count($orders)==($key+1)) {
							echo "['" . $order['year_quarter'] . "',". $order['orders'] . ",". $order['customers'] . ",". $order['products'] . "]";
						} else {
							echo "['" . $order['year_quarter'] . "',". $order['orders'] . ",". $order['customers'] . ",". $order['products'] . "],";
						}
					}	
			} elseif ($orders && $filter_group == 'year') {
				echo "['" . $column_year . "','". $column_orders . "','" . $column_customers . "','" . $column_products . "'],";
					foreach ($orders as $key => $order) {
						if (count($orders)==($key+1)) {
							echo "['" . $order['year'] . "',". $order['orders'] . ",". $order['customers'] . ",". $order['products'] . "]";
						} else {
							echo "['" . $order['year'] . "',". $order['orders'] . ",". $order['customers'] . ",". $order['products'] . "],";
						}
					}	
			} 
			;?>
		]);

        var options = {
			<?php if ($orders && $filter_group == 'month') {
				echo "title: 'Sales Summary by Month'";
			} elseif ($orders && $filter_group == 'quarter') {
				echo "title: 'Sales Summary by Quarter'";	
			} elseif ($orders && $filter_group == 'year') {
				echo "title: 'Sales Summary by Year'";	
			} 
			;?>,
			width: 625,	
			height: 266,  
			colors: ['#edc240', '#9dc7e8', '#CCCCCC'],
			chartArea: {left: 45, top: 30, width: "75%", height: "70%"},
			hAxis: {direction: -1},
			pointSize: '4',
			legend: {position: 'right', alignment: 'start', textStyle: {color: '#666666', fontSize: 12}}
		};

			var chart = new google.visualization.LineChart(document.getElementById('chart1_sales_summary'));
			chart.draw(data, options);
	}
//--></script>
<script type="text/javascript"><!--
	google.load('visualization', '1', {packages: ['corechart']});
      google.setOnLoadCallback(drawChart);      
	  function drawChart() { 
   		var data = google.visualization.arrayToDataTable([
			<?php if ($orders && $filter_group == 'month') {
				echo "['" . $column_month . "','". $column_total . "'],";
					foreach ($orders as $key => $order) {
						if (count($orders)==($key+1)) {
							echo "['" . $order['year_month'] . "',". $order['gtotal'] . "]";
						} else {
							echo "['" . $order['year_month'] . "',". $order['gtotal'] . "],";
						}
					}	
			} elseif ($orders && $filter_group == 'quarter') {
				echo "['" . $column_quarter . "','". $column_total . "'],";
					foreach ($orders as $key => $order) {
						if (count($orders)==($key+1)) {
							echo "['" . $order['year_quarter'] . "',". $order['gtotal'] . "]";
						} else {
							echo "['" . $order['year_quarter'] . "',". $order['gtotal'] . "],";
						}
					}	
			} elseif ($orders && $filter_group == 'year') {
				echo "['" . $column_year . "','". $column_total . "'],";
					foreach ($orders as $key => $order) {
						if (count($orders)==($key+1)) {
							echo "['" . $order['year'] . "',". $order['gtotal'] . "]";
						} else {
							echo "['" . $order['year'] . "',". $order['gtotal'] . "],";
						}
					}	
			} 
			;?>
		]);

        var options = {
			<?php if ($orders && $filter_group == 'month') {
				echo "title: 'Sales Summary by Month'";
			} elseif ($orders && $filter_group == 'quarter') {
				echo "title: 'Sales Summary by Quarter'";	
			} elseif ($orders && $filter_group == 'year') {
				echo "title: 'Sales Summary by Year'";	
			} 
			;?>,				
			width: 625,	
			height: 266,  
			colors: ['#b5e08b', '#ed9999', '#739cc3'],
			chartArea: {left: 45, top: 30, width: "75%", height: "70%"},
			hAxis: {direction: -1},
			legend: {position: 'right', alignment: 'start', textStyle: {color: '#666666', fontSize: 12}},				
			seriesType: "bars",
			series: {2: {type: "area", lineWidth: '3', pointSize: '5'}}
		};

			var chart = new google.visualization.ComboChart(document.getElementById('chart2_sales_summary'));
			chart.draw(data, options);
	}
//--></script>
	<?php } ?>
	<?php } elseif ($filter_report == 'day_of_week') { ?>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript"><!--
	google.load('visualization', '1', {packages: ['corechart']});
      google.setOnLoadCallback(drawChart);      
	  function drawChart() {        
	  	var data = google.visualization.arrayToDataTable([
			<?php echo "['" . $column_day_of_week . "','". $column_orders . "','" . $column_customers . "','" . $column_products . "'],";
					foreach ($orders as $key => $order) {
						if (count($orders)==($key+1)) {
							echo "['" . $order['day_of_week'] . "',". $order['orders'] . ",". $order['customers'] . ",". $order['products'] . "]";
						} else {
							echo "['" . $order['day_of_week'] . "',". $order['orders'] . ",". $order['customers'] . ",". $order['products'] . "],";
						}
					}
			;?>
		]);

        var options = {
			title: 'Sales by Day of Week',			
			width: 625,	
			height: 266,  
			colors: ['#edc240', '#9dc7e8', '#CCCCCC'],
			chartArea: {left: 45, top: 30, width: "75%", height: "70%"},
			pointSize: '4',
			legend: {position: 'right', alignment: 'start', textStyle: {color: '#666666', fontSize: 12}}
		};

			var chart = new google.visualization.LineChart(document.getElementById('chart1_day_of_week'));
			chart.draw(data, options);
	}
//--></script>
<script type="text/javascript"><!--
	google.load('visualization', '1', {packages: ['corechart']});
      google.setOnLoadCallback(drawChart);      
	  function drawChart() { 
   		var data = google.visualization.arrayToDataTable([
			<?php echo "['" . $column_day_of_week . "','". $column_total . "'],";
					foreach ($orders as $key => $order) {
						if (count($orders)==($key+1)) {
							echo "['" . $order['day_of_week'] . "',". $order['gtotal'] . "]";
						} else {
							echo "['" . $order['day_of_week'] . "',". $order['gtotal'] . "],";
						}
					}
			;?>
		]);

        var options = {
			title: 'Sales by Day of Week',				
			width: 625,	
			height: 266,  
			colors: ['#b5e08b', '#ed9999', '#739cc3'],
			chartArea: {left: 45, top: 30, width: "75%", height: "70%"},
			legend: {position: 'right', alignment: 'start', textStyle: {color: '#666666', fontSize: 12}},				
			seriesType: "bars",
			series: {2: {type: "area", lineWidth: '3', pointSize: '5'}}
		};

			var chart = new google.visualization.ComboChart(document.getElementById('chart2_day_of_week'));
			chart.draw(data, options);
	}
//--></script>
    <?php } elseif ($filter_report == 'hour') { ?>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript"><!--
	google.load('visualization', '1', {packages: ['corechart']});
      google.setOnLoadCallback(drawChart);      
	  function drawChart() {        
	  	var data = google.visualization.arrayToDataTable([
			<?php echo "['" . $column_hour . "','". $column_orders . "','" . $column_customers . "','" . $column_products . "'],";
					foreach ($orders as $key => $order) {
						if (count($orders)==($key+1)) {
							echo "['" . $order['hour'] . "',". $order['orders'] . ",". $order['customers'] . ",". $order['products'] . "]";
						} else {
							echo "['" . $order['hour'] . "',". $order['orders'] . ",". $order['customers'] . ",". $order['products'] . "],";
						}
					}
			;?>
		]);

        var options = {
			title: 'Sales by Hour',			
			width: 625,	
			height: 266,  
			colors: ['#edc240', '#9dc7e8', '#CCCCCC'],
			chartArea: {left: 45, top: 30, width: "75%", height: "70%"},
			pointSize: '4',
			legend: {position: 'right', alignment: 'start', textStyle: {color: '#666666', fontSize: 12}}
		};

			var chart = new google.visualization.LineChart(document.getElementById('chart1_hour'));
			chart.draw(data, options);
	}
//--></script>
<script type="text/javascript"><!--
	google.load('visualization', '1', {packages: ['corechart']});
      google.setOnLoadCallback(drawChart);      
	  function drawChart() { 
   		var data = google.visualization.arrayToDataTable([
			<?php echo "['" . $column_hour . "','". $column_total . "'],";
					foreach ($orders as $key => $order) {
						if (count($orders)==($key+1)) {
							echo "['" . $order['hour'] . "',". $order['gtotal'] . "]";
						} else {
							echo "['" . $order['hour'] . "',". $order['gtotal'] . "],";
						}
					}
			;?>
		]);

        var options = {
			title: 'Sales by Hour',				
			width: 625,	
			height: 266,  
			colors: ['#b5e08b', '#ed9999', '#739cc3'],
			chartArea: {left: 45, top: 30, width: "75%", height: "70%"},
			legend: {position: 'right', alignment: 'start', textStyle: {color: '#666666', fontSize: 12}},				
			seriesType: "bars",
			series: {2: {type: "area", lineWidth: '3', pointSize: '5'}}
		};

			var chart = new google.visualization.ComboChart(document.getElementById('chart2_hour'));
			chart.draw(data, options);
	}
//--></script>
	<?php } elseif ($filter_report == 'store') { ?>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript"><!--
	google.load('visualization', '1', {packages: ['corechart']});
      google.setOnLoadCallback(drawChart);      
	  function drawChart() {        
	  	var data = google.visualization.arrayToDataTable([
			<?php echo "['" . $column_store . "','". $column_orders . "','" . $column_customers . "','" . $column_products . "'],";
					foreach ($orders as $key => $order) {
						if (count($orders)==($key+1)) {
							echo "['" . $order['store_name'] . "',". $order['orders'] . ",". $order['customers'] . ",". $order['products'] . "]";
						} else {
							echo "['" . $order['store_name'] . "',". $order['orders'] . ",". $order['customers'] . ",". $order['products'] . "],";
						}
					}
			;?>
		]);

        var options = {
			title: 'Sales by Store',			
			width: 625,	
			height: 266,  
			colors: ['#edc240', '#9dc7e8', '#CCCCCC'],
			chartArea: {left: 45, top: 30, width: "75%", height: "70%"},
			pointSize: '4',
			legend: {position: 'right', alignment: 'start', textStyle: {color: '#666666', fontSize: 12}}
		};

			var chart = new google.visualization.LineChart(document.getElementById('chart1_store'));
			chart.draw(data, options);
	}
//--></script>
<script type="text/javascript"><!--
	google.load('visualization', '1', {packages: ['corechart']});
      google.setOnLoadCallback(drawChart);      
	  function drawChart() { 
   		var data = google.visualization.arrayToDataTable([
			<?php echo "['" . $column_store . "','". $column_total . "'],";
					foreach ($orders as $key => $order) {
						if (count($orders)==($key+1)) {
							echo "['" . $order['store_name'] . "',". $order['gtotal'] . "]";
						} else {
							echo "['" . $order['store_name'] . "',". $order['gtotal'] . "],";
						}
					}
			;?>
		]);

        var options = {
			title: 'Sales by Store',				
			width: 625,	
			height: 266,  
			colors: ['#b5e08b', '#ed9999', '#739cc3'],
			chartArea: {left: 45, top: 30, width: "75%", height: "70%"},
			legend: {position: 'right', alignment: 'start', textStyle: {color: '#666666', fontSize: 12}},				
			seriesType: "bars",
			series: {2: {type: "area", lineWidth: '3', pointSize: '5'}}
		};

			var chart = new google.visualization.ComboChart(document.getElementById('chart2_store'));
			chart.draw(data, options);
	}
//--></script>
	<?php } elseif ($filter_report == 'customer_group') { ?>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript"><!--
	google.load('visualization', '1', {packages: ['corechart']});
      google.setOnLoadCallback(drawChart);      
	  function drawChart() {        
	  	var data = google.visualization.arrayToDataTable([
			<?php echo "['" . $column_customer_group . "','". $column_orders . "','" . $column_customers . "','" . $column_products . "'],";
					foreach ($orders as $key => $order) {
						if (count($orders)==($key+1)) {
							echo "['" . $order['customer_group'] . "',". $order['orders'] . ",". $order['customers'] . ",". $order['products'] . "]";
						} else {
							echo "['" . $order['customer_group'] . "',". $order['orders'] . ",". $order['customers'] . ",". $order['products'] . "],";
						}
					}
			;?>
		]);

        var options = {
			title: 'Sales by Customer Group',			
			width: 625,	
			height: 266,  
			colors: ['#edc240', '#9dc7e8', '#CCCCCC'],
			chartArea: {left: 45, top: 30, width: "75%", height: "70%"},
			pointSize: '4',
			legend: {position: 'right', alignment: 'start', textStyle: {color: '#666666', fontSize: 12}}
		};

			var chart = new google.visualization.LineChart(document.getElementById('chart1_customer_group'));
			chart.draw(data, options);
	}
//--></script>
<script type="text/javascript"><!--
	google.load('visualization', '1', {packages: ['corechart']});
      google.setOnLoadCallback(drawChart);      
	  function drawChart() { 
   		var data = google.visualization.arrayToDataTable([
			<?php echo "['" . $column_customer_group . "','". $column_total . "'],";
					foreach ($orders as $key => $order) {
						if (count($orders)==($key+1)) {
							echo "['" . $order['customer_group'] . "',". $order['gtotal'] . "]";
						} else {
							echo "['" . $order['customer_group'] . "',". $order['gtotal'] . "],";
						}
					}
			;?>
		]);

        var options = {
			title: 'Sales by Customer Group',				
			width: 625,	
			height: 266,  
			colors: ['#b5e08b', '#ed9999', '#739cc3'],
			chartArea: {left: 45, top: 30, width: "75%", height: "70%"},
			legend: {position: 'right', alignment: 'start', textStyle: {color: '#666666', fontSize: 12}},				
			seriesType: "bars",
			series: {2: {type: "area", lineWidth: '3', pointSize: '5'}}
		};

			var chart = new google.visualization.ComboChart(document.getElementById('chart2_customer_group'));
			chart.draw(data, options);
	}
//--></script>
	<?php } elseif ($filter_report == 'country') { ?>
  <script type='text/javascript' src='https://www.google.com/jsapi'></script>
  <script type='text/javascript'>
   google.load('visualization', '1', {'packages': ['geomap']});
   google.setOnLoadCallback(drawMap);
    function drawMap() {
      var data = google.visualization.arrayToDataTable([
			<?php echo "['" . $column_country ."','". $column_orders . "'],";
					foreach ($orders as $key => $order) {
						if (count($orders)==($key+1)) {
							echo "['". $order['payment_country'] . "',". $order['orders'] . "]";
						} else {
							echo "['". $order['payment_country'] . "',". $order['orders'] . "],";
						}
					}
			;?>
		]);

     	var options = {};
      		options['dataMode'] = 'regions';

      var container = document.getElementById('chart_country');
      var geomap = new google.visualization.GeoMap(container);
      geomap.draw(data, options);
  };
  </script>
	<?php } elseif ($filter_report == 'payment_method') { ?>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript"><!--
	google.load('visualization', '1', {packages: ['corechart']});
      google.setOnLoadCallback(drawChart);      
	  function drawChart() { 
   		var data = google.visualization.arrayToDataTable([
			<?php echo "['" . $column_payment_method ."','". $column_orders . "'],";
					foreach ($orders as $key => $order) {
						if (count($orders)==($key+1)) {
							echo "['". $order['payment_method'] . "',". $order['orders'] . "]";
						} else {
							echo "['". $order['payment_method'] . "',". $order['orders'] . "],";
						}
					}
			;?>
		]);


        var options = {
			title: 'Sales by Payment Method',
			pieSliceText: 'none',
			tooltip: {text: 'value'},
			chartArea: {left: 45, top: 55, width: "75%", height: "65%"},
        };

			var chart = new google.visualization.PieChart(document.getElementById('chart_payment_method'));
			chart.draw(data, options);
	}
//--></script>
	<?php } elseif ($filter_report == 'shipping_method') { ?>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript"><!--
	google.load('visualization', '1', {packages: ['corechart']});
      google.setOnLoadCallback(drawChart);      
	  function drawChart() { 
   		var data = google.visualization.arrayToDataTable([
			<?php echo "['" . $column_shipping_method ."','". $column_orders . "'],";
					foreach ($orders as $key => $order) {
						if (count($orders)==($key+1)) {
							echo "['". $order['shipping_method'] . "',". $order['orders'] . "]";
						} else {
							echo "['". $order['shipping_method'] . "',". $order['orders'] . "],";
						}
					}
			;?>
		]);


        var options = {
			title: 'Sales by Shipping Method',
			pieSliceText: 'none',
			tooltip: {text: 'value'},
			chartArea: {left: 45, top: 55, width: "75%", height: "65%"},
        };

			var chart = new google.visualization.PieChart(document.getElementById('chart_shipping_method'));
			chart.draw(data, options);
	}
//--></script>
	<?php } ?>
<?php } ?>
<?php echo $footer; ?>
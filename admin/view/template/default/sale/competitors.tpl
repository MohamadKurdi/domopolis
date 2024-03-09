<?php echo $header; ?>
<style>
	.swal-wide{
    width:650px !important;
	}
</style>
<div id="content">
	<div class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
			<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
		<?php } ?>
	</div>
	<?php if ($error_warning) { ?>
		<div class="warning"><?php echo $error_warning; ?></div>
	<?php } ?>
	<?php if ($success) { ?>
		<div class="success"><?php echo $success; ?></div>
	<?php } ?>
	<div class="box">
		<div class="heading order_head">
			<h1>Мониторинг цен конкурентов</h1>
			<div class="buttons">
			</div>
		</div>	
		<div style="clear:both;"></div>
		<div class="content">
			<form action="" method="post" enctype="multipart/form-data" id="form">
				<table style="width: 100%;">
					<tbody>
					</tbody>
				</table>
				<div class="filter_bord"></div>
				<div class="pagination" style="margin-bottom:5px;"><?php echo $pagination; ?></div>					
				<table class="list">
					<thead>
						<tr>
							
						</tr>
					</thead>
					<tbody>
						
						<?php if ($products) { ?>
							<?php foreach ($products as $product) { ?>
								<tr>								
								</tr>
							<?php } ?>
							<?php } else { ?>
							<tr>
								<td class="center" colspan="10">Нет результатов</td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</form>
			<div class="pagination"><?php echo $pagination; ?></div>
		</div>
	</div>
</div>
<div id="mailpreview"></div>
<script>
	$('.load_order_history').click(function(){
		$.ajax({
			url: 'index.php?route=sale/order&ajax=1&limit=200&token=<?php echo $token; ?>&filter_customer=' +  $(this).attr('data-customer-id'),
			dataType: 'html',
			success : function(html){
				$('#mailpreview').html(html).dialog({width:900, modal:true,resizable:true,position:{my: 'center', at:'center center', of: window}, closeOnEscape: true})				
			}
		})	
	});	
	$('.load_call_history').click(function(){
		$.ajax({
			url: 'index.php?route=sale/customer/callshistory&ajax=1&limit=200&token=<?php echo $token; ?>&customer_id=' +  $(this).attr('data-customer-id'),
			dataType: 'html',
			success : function(html){
				$('#mailpreview').html(html).dialog({width:900, modal:true,resizable:true,position:{my: 'center', at:'center center', of: window}, closeOnEscape: true})				
			}
		})	
	});	
</script>
<script type="text/javascript"><!--	
	
	function filter() {
		url = 'index.php?route=sale/customer&token=<?php echo $token; ?>';
		
		var filter_name = $('input[name=\'filter_name\']').attr('value');
		
		if (filter_name) {
			url += '&filter_name=' + encodeURIComponent(filter_name);
		}
		
		var filter_email = $('input[name=\'filter_email\']').attr('value');
		
		if (filter_email) {
			url += '&filter_email=' + encodeURIComponent(filter_email);
		}
		
		var filter_phone = $('input[name=\'filter_phone\']').attr('value');
		
		if (filter_phone) {
			url += '&filter_phone=' + encodeURIComponent(filter_phone);
		}
		
		
		var filter_customer_group_id = $('select[name=\'filter_customer_group_id\']').attr('value');
		
		if (filter_customer_group_id != '*') {
			url += '&filter_customer_group_id=' + encodeURIComponent(filter_customer_group_id);
		}
		
		var filter_status = $('select[name=\'filter_status\']').attr('value');
		
		if (filter_status != '*') {
			url += '&filter_status=' + encodeURIComponent(filter_status);
		}
		
		var filter_gender = $('select[name=\'filter_gender\']').attr('value');
		
		if (filter_gender != '*') {
			url += '&filter_gender=' + encodeURIComponent(filter_gender);
		}
		
		var filter_mail_status = $('select[name=\'filter_mail_status\']').attr('value');
		
		if (filter_mail_status != '*') {
			url += '&filter_mail_status=' + encodeURIComponent(filter_mail_status);
		}
		
		var filter_campaing_id = $('select[name=\'campaing_id\']').attr('value');
		
		if (filter_campaing_id != '*') {
			url += '&campaing_id=' + encodeURIComponent(filter_campaing_id);
		}
		
		var filter_mail_opened = $('input[name=\'mail_opened\']:checked').val();
		
		if (filter_mail_opened !== undefined) {
			url += '&filter_mail_opened=1';
		}
		
		var filter_mail_checked = $('input[name=\'mail_checked\']:checked').val();
		
		if (filter_mail_checked !== undefined) {
			url += '&filter_mail_checked=1';
		}
		
		var filter_push_signed = $('input[name=\'push_sign\']:checked').val();
		
		if (filter_push_signed  !== undefined) {
			url += '&filter_push_signed=1';
		}
		
		var filter_segment_intersection = $('input[name=\'filter_segment_intersection\']:checked').val();
		
		if (filter_segment_intersection  !== undefined) {
			url += '&filter_segment_intersection=1';
		}
		
		var filter_has_discount = $('input[name=\'filter_has_discount\']:checked').val();
		
		if (filter_has_discount !== undefined) {
			url += '&filter_has_discount=1';
		}
		
		var filter_no_discount = $('input[name=\'filter_no_discount\']:checked').val();
		
		if (filter_no_discount !== undefined) {
			url += '&filter_no_discount=1';
		}
		
		var filter_approved = $('select[name=\'filter_approved\']').attr('value');
		
		if (filter_approved != '*') {
			url += '&filter_approved=' + encodeURIComponent(filter_approved);
		}
		
		var filter_ip = $('input[name=\'filter_ip\']').attr('value');
		
		if (filter_ip) {
			url += '&filter_ip=' + encodeURIComponent(filter_ip);
		}
		
		var filter_country_id = $('select[name=\'filter_country_id\']').attr('value');
		
		if (filter_country_id != '*') {
			url += '&filter_country_id=' + encodeURIComponent(filter_country_id);
		}
		
		var filter_source = $('select[name=\'filter_source\']').attr('value');
		
		if (filter_source != '*') {
			url += '&filter_source=' + encodeURIComponent(filter_source);
		}
		
		var filter_date_added = $('input[name=\'filter_date_added\']').attr('value');
		
		if (filter_date_added) {
			url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
		}
		
		var filter_date_added = $('input[name=\'filter_date_added\']').attr('value');
		
		if (filter_date_added) {
			url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
		}
		
		var filter_birthday_from = $('input[name=\'filter_birthday_from\']').attr('value');
		
		if (filter_birthday_from) {
			url += '&filter_birthday_from=' + encodeURIComponent(filter_birthday_from);
		}
		
		var filter_birthday_to = $('input[name=\'filter_birthday_to\']').attr('value');
		
		if (filter_birthday_to) {
			url += '&filter_birthday_to=' + encodeURIComponent(filter_birthday_to);
		}
		
		var filter_order_count = $('input[name=\'filter_order_count\']').attr('value');
		
		if (filter_order_count) {
			url += '&filter_order_count=' + encodeURIComponent(filter_order_count);
		}
		
		var filter_order_good_count = $('input[name=\'filter_order_good_count\']').attr('value');
		
		if (filter_order_good_count) {
			url += '&filter_order_good_count=' + encodeURIComponent(filter_order_good_count);
		}
		
		var filter_total_sum = $('input[name=\'filter_total_sum\']').attr('value');
		
		if (filter_total_sum) {
			url += '&filter_total_sum=' + encodeURIComponent(filter_total_sum);
		}
		
		var filter_avg_cheque = $('input[name=\'filter_avg_cheque\']').attr('value');
		
		if (filter_avg_cheque) {
			url += '&filter_avg_cheque=' + encodeURIComponent(filter_avg_cheque);
		}
		
		var filter_interest_brand = $('input[name=\'filter_interest_brand\']').attr('value');
		
		if (filter_interest_brand) {
			url += '&filter_interest_brand=' + encodeURIComponent(filter_interest_brand);
		}
		
		var filter_interest_category = $('input[name=\'filter_interest_category\']').attr('value');
		
		if (filter_interest_category) {
			url += '&filter_interest_category=' + encodeURIComponent(filter_interest_category);
		}
		
		var filter_segment_id = $('input:checkbox:checked.filter_segment_id').map(function(){
		return this.value; }).get().join(",");
		
		if (filter_segment_id) {
			url += '&filter_segment_id=' + encodeURIComponent(filter_segment_id);
		}
		
		location = url;
	}
//--></script>
<script type="text/javascript"><!--
	$(document).ready(function() {
		$('#date').datepicker({dateFormat: 'yy-mm-dd'});
	});
	$(document).ready(function() {
		$('#date_bf').datepicker({dateFormat: 'mm-dd'});
		$('#date_bt').datepicker({dateFormat: 'mm-dd'});
	});
//--></script>
<?php echo $footer; ?>

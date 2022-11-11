<?php echo $header; ?>
<div id="content">
	<div class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
		<?php echo $breadcrumb['separator']; ?>
		<?php if ($breadcrumb['href']) { ?>
		<a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
		<?php } else { ?>
		<?php echo $breadcrumb['text']; ?>
		<?php } ?>
		<?php } ?>
	</div>
	<?php if ($error_warning) { ?>
	<div class="warning"><?php echo $error_warning; ?></div>
	<?php } ?>
	<?php if ($success) { ?>
	<div class="success"><?php echo $success; ?></div>
	<?php } ?>
	<div class="box">
		<div class="heading">
			<h1><img src="view/image/order.png" alt="" /> <?php echo $heading_title; ?></h1>
			<div class="buttons">
				<a href="javascript:void(0)" id="send-orders" class="button"><?php echo $button_create; ?></a>
				<a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a>
			</div>
		</div>
		<div class="content">
			<table class="list">
				<thead>
					<tr>
					<td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
					<td class="right"><a href="<?php echo $sort_order; ?>" <?php if ($sort == 'o.order_id') echo 'class="' . $order . '"'; ?>><?php echo $column_order_id; ?></a></td>
					<td class="left"><a href="<?php echo $sort_customer; ?>" <?php if ($sort == 'customer') echo 'class="' . $order . '"'; ?>><?php echo $column_customer; ?></a></td>
					<td class="left"><a href="<?php echo $sort_status; ?>" <?php if ($sort == 'status') echo 'class="' . $order . '"'; ?>><?php echo $column_status; ?></a></td>
					<td class="right"><a href="<?php echo $sort_total; ?>" <?php if ($sort == 'o.total') echo 'class="' . $order . '"'; ?>><?php echo $column_total; ?></a></td>
					<td class="left"><a href="<?php echo $sort_date_added; ?>" <?php if ($sort == 'o.date_added') echo 'class="' . $order . '"'; ?>><?php echo $column_date_added; ?></a></td>
					<td class="right"><?php echo $column_action; ?></td>
					</tr>
				</thead>
				<tbody>
					<tr class="filter">
						<td></td>
						<td align="right"><input type="text" name="filter_order_id" value="<?php echo $filter_order_id; ?>" size="4" style="text-align: right;" /></td>
						<td><input type="text" name="filter_customer" value="<?php echo $filter_customer; ?>" /></td>
						<td>
							<select name="filter_order_status_id">
								<option value="*"></option>
								<?php foreach ($order_statuses as $order_status) { ?>
								<option value="<?php echo $order_status['order_status_id']; ?>" <?php if ($order_status['order_status_id'] == $filter_order_status_id) echo 'selected="selected"'; ?>><?php echo $order_status['name']; ?></option>
								<?php } ?>
							</select>
						</td>
						<td align="right"><input type="text" name="filter_total" value="<?php echo $filter_total; ?>" size="4" style="text-align: right;" /></td>
						<td><input type="text" name="filter_date_added" value="<?php echo $filter_date_added; ?>" size="12" class="date" /></td>
						<td align="right"><a onclick="filter();" class="button"><?php echo $button_filter; ?></a></td>
					</tr>
					<?php if ($orders) { ?>
					<?php foreach ($orders as $order) { ?>
					<tr>
						<td style="text-align: center;"><input type="checkbox" name="selected[]" value="<?php echo $order['order_id']; ?>" <?php if ($order['selected']) echo 'checked="checked"'; ?> /></td>
						<td class="right"><?php echo $order['order_id']; ?></td>
						<td class="left"><?php echo $order['customer']; ?></td>
						<td class="left"><?php echo $order['status']; ?></td>
						<td class="right"><?php echo $order['total']; ?></td>
						<td class="left"><?php echo $order['date_added']; ?></td>
						<td class="right action">
							<?php foreach ($order['action'] as $action) { ?>
							<a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> 
							<?php } ?>
						</td>
					</tr>
					<?php } ?>
					<?php } else { ?>
					<tr>
						<td class="center" colspan="8"><?php echo $text_no_results; ?></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
			<div class="pagination">
				<?php if ($pagination) { ?>
					<div class="limit">
						<select onchange="location = $(this).val();">
							<?php foreach ($limits as $key => $url) { ?>
							<option <?php if ($limit == $key) echo 'selected="selected"'; ?> value="<?php echo $url; ?>"><?php echo $key; ?></option>
							<?php } ?>
						</select>
					</div>
				<?php } ?>	
				<?php echo $pagination; ?>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript"><!--

$('#send-orders').click(function(event){
	
	var orders = '';
	
	$('input[name*=\'selected\']:checked').each(function(){
		//orders.push($(this).val());
		orders += '&orders[]=' + $(this).val();
	})
	
	//alert(orders);
	
	if (orders.length) {
		window.location = '<?php echo $create; ?>' + orders;
	}
	
});

function filter() {
	url = 'index.php?route=module/cdek_integrator/order&token=<?php echo $token; ?>';
	
	var filter_order_id = $('input[name=\'filter_order_id\']').attr('value');
	
	if (filter_order_id) {
		url += '&filter_order_id=' + encodeURIComponent(filter_order_id);
	}
	
	var filter_customer = $('input[name=\'filter_customer\']').attr('value');
	
	if (filter_customer) {
		url += '&filter_customer=' + encodeURIComponent(filter_customer);
	}
	
	var filter_order_status_id = $('select[name=\'filter_order_status_id\']').attr('value');
	
	if (filter_order_status_id != '*') {
		url += '&filter_order_status_id=' + encodeURIComponent(filter_order_status_id);
	}	

	var filter_total = $('input[name=\'filter_total\']').attr('value');

	if (filter_total) {
		url += '&filter_total=' + encodeURIComponent(filter_total);
	}	
	
	var filter_date_added = $('input[name=\'filter_date_added\']').attr('value');
	
	if (filter_date_added) {
		url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
	}
	
	var filter_date_modified = $('input[name=\'filter_date_modified\']').attr('value');
	
	if (filter_date_modified) {
		url += '&filter_date_modified=' + encodeURIComponent(filter_date_modified);
	}
				
	location = url;
}
//--></script>  
<script type="text/javascript"><!--
$(document).ready(function() {
	$('.date').datepicker({dateFormat: 'yy-mm-dd'});
});
//--></script> 
<script type="text/javascript"><!--
$('#form input').keydown(function(e) {
	if (e.keyCode == 13) {
		filter();
	}
});
//--></script> 
<script type="text/javascript"><!--
$.widget('custom.catcomplete', $.ui.autocomplete, {
	_renderMenu: function(ul, items) {
		var self = this, currentCategory = '';
		
		$.each(items, function(index, item) {
			if (item.category != currentCategory) {
				ul.append('<li class="ui-autocomplete-category">' + item.category + '</li>');
				
				currentCategory = item.category;
			}
			
			self._renderItem(ul, item);
		});
	}
});

$('input[name=\'filter_customer\']').catcomplete({
	delay: 0,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=sale/customer/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {		
				response($.map(json, function(item) {
					return {
						category: item.customer_group,
						label: item.name,
						value: item.customer_id
					}
				}));
			}
		});
	}, 
	select: function(event, ui) {
		$('input[name=\'filter_customer\']').val(ui.item.label);
						
		return false;
	}
});
//--></script> 
<?php echo $footer; ?>
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
				<a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a>
			</div>
		</div>
		<div class="content">
			<table class="list">
				<thead>
					<tr>
						<td class="left"><a href="<?php echo $sort_order_id; ?>"<?php if ($sort == 'o.order_id') { ?> class="<?php echo strtolower($order); ?>"<?php } ?>>№ заказа</a></td>
						<td class="left"><a href="<?php echo $sort_dispatch_number; ?>"<?php if ($sort == 'd.dispatch_number') { ?> class="<?php echo strtolower($order); ?>"<?php } ?>>Номер отправления</a></td>
						<td class="left"><a href="<?php echo $sort_order_id; ?>"<?php if ($sort == 'o.order_id') { ?> class="<?php echo strtolower($order); ?>"<?php } ?>>Акт приема-передачи</a></td>
						<td class="left"><a href="<?php echo $sort_act_number; ?>"<?php if ($sort == 'o.act_number') { ?> class="<?php echo strtolower($order); ?>"<?php } ?>>Дата отгрузки</a></td>
						<td class="left"><a href="<?php echo $sort_city_from; ?>"<?php if ($sort == 'o.city_name') { ?> class="<?php echo strtolower($order); ?>"<?php } ?>>Откуда</a></td>
						<td class="left"><a href="<?php echo $sort_city_to; ?>"<?php if ($sort == 'o.recipient_city_name') { ?> class="<?php echo strtolower($order); ?>"<?php } ?>>Куда</a></td>
						<td class="left"><a href="<?php echo $sort_status; ?>"<?php if ($sort == 'o.status_id') { ?> class="<?php echo strtolower($order); ?>"<?php } ?>>Статус</a></td>
						<td class="left"><a href="<?php echo $sort_total; ?>"<?php if ($sort == 'o.delivery_cost') { ?> class="<?php echo strtolower($order); ?>"<?php } ?>>Стоимость доставки</a></td>
						<td class="right"><?php echo $column_action; ?></td>
					</tr>
				</thead>
				<tbody>
					<tr class="filter">
						<td class="left"><input type="text" name="filter_order_id" value="<?php echo $filter_order_id; ?>" /></td>
						<td class="left"><input type="text" name="filter_dispatch_number" value="<?php echo $filter_dispatch_number; ?>" /></td>
						<td class="left"><input type="text" name="filter_act_number" value="<?php echo $filter_act_number; ?>" /></td>
						<td class="left"><input type="text" name="filter_date" value="<?php echo $filter_date; ?>" class="date" /></td>
						<td class="left"><input type="text" name="filter_city_from" value="<?php echo $filter_city_from; ?>" /></td>
						<td class="left"><input type="text" name="filter_city_to" value="<?php echo $filter_city_to; ?>" /></td>
						<td class="left">
							<select name="filter_status_id">
								<option value="*"></option>
								<?php foreach ($statuses as $status_id => $status_info) { ?>
								<option <?php if ($filter_status_id == $status_id) echo 'selected="selected"'; ?> value="<?php echo $status_id; ?>"><?php echo $status_info['title']; ?></option>
								<?php } ?>
							</select>
						</td>
						<td class="left"><input type="text" name="filter_total" value="<?php echo $filter_total; ?>" /></td>
						<td class="right"><a onclick="filter();" class="button"><?php echo $button_filter; ?></a></td>
					</tr>
					<?php if (!empty($dispatches)) { ?>
					<?php foreach ($dispatches as $dispatch_info) { ?>
					<tr>
						<td class="left"><?php echo $dispatch_info['order_id']; ?></td>
						<td class="left"><?php echo $dispatch_info['dispatch_number']; ?></td>
						<td class="left">
							<?php if ($dispatch_info['act_number']) { ?>
							<?php echo $dispatch_info['act_number']; ?>
							<?php } else { ?>
							<a class="js sync-row">Синхронизовать</a>
							<?php } ?>
						</td>
						<td class="left"><?php echo $dispatch_info['date']; ?></td>
						<td class="left"><?php echo $dispatch_info['city_name']; ?></td>
						<td class="left"><?php echo $dispatch_info['recipient_city_name']; ?></td>
						<td class="left"><?php echo $dispatch_info['status']; ?><span class="help"><?php echo $dispatch_info['status_date']; ?></span></td>
						<td class="left">
							<?php if ($dispatch_info['cost']) { ?>
							<?php echo $dispatch_info['cost']; ?>
							<?php } else { ?>
							<a class="js sync-row">Синхронизовать</a>
							<?php } ?>
						</td>
						<td class="right action">
							<?php foreach ($dispatch_info['action'] as $action) { ?>
							<a href="<?php echo $action['href']; ?>" <?php if (!empty($action['class'])) echo 'class="' . $action['class'] . '"'; ?>><?php echo $action['text']; ?></a>
							<?php } ?>
						</td>
					</tr>
					<?php } ?>
					<?php } else { ?>
					<tr>
						<td class="center" colspan="9"><?php echo $text_no_results; ?></td>
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

$('.right.action a.sync').live('click', function(event){
	
	ajaxSend(this, {
		beforeSend: function(context) {
			
			$(context).removeClass('right').addClass('center').css('width', $(context).width()).append('<img class="loader" src="view/image/cdek_integrator/loader.gif" alt="Загрузка..." title="Загрузка..." />');
			$('a', context).hide();
			
		},
		complete: function(context) {
			
			$('a', context).show();
			
			$('.loader', context).remove();
			
			$(context).removeClass('center').addClass('right');
			
		},
		callback: function(el, json){
		
			var context = $(el).closest('tr');
			
			if (json.status != 'error') {
				
				$('td', context).animate({'background-color': '#000000'}, 'fast', function(){
					
					$('td:eq(2)', context).html(json.act_number);
					$('td:eq(3)', context).html(json.date);
					$('td:eq(4)', context).html(json.city_name);
					$('td:eq(5)', context).html(json.recipient_city_name);
					$('td:eq(6)', context).html(json.status_title + '<span class="help">' + json.status_date + '</span>');
					$('td:eq(7)', context).html(json.cost);
					
					$('td', context).animate({'background-color': '#FFFFFF'}, 'fast');
					
				});
				
			} else {
				
				$('.box').before('<div class="warning">' + json.message + '</div>');
				
			}
			
		}
	});
	
	event.preventDefault();
	
});

$('a.js.sync-row').live('click', function(event){
	
	var row = $(this).closest('tr');
	
	$('.right.action a.sync', row).trigger('click');
	
});

$('tr.filter input').keydown(function(e) {
	if (e.keyCode == 13) {
		filter();
	}
});

function filter() {
	
	url = 'index.php?route=module/cdek_integrator/dispatch&token=<?php echo $token; ?>';
	
	var filter_order_id = $('input[name=\'filter_order_id\']').attr('value');
	
	if (filter_order_id) {
		url += '&filter_order_id=' + encodeURIComponent(filter_order_id);
	}
	
	var filter_dispatch_number = $('input[name=\'filter_dispatch_number\']').attr('value');
	
	if (filter_dispatch_number) {
		url += '&filter_dispatch_number=' + encodeURIComponent(filter_dispatch_number);
	}
	
	var filter_act_number = $('input[name=\'filter_act_number\']').attr('value');
	
	if (filter_act_number) {
		url += '&filter_act_number=' + encodeURIComponent(filter_act_number);
	}
	
	var filter_date = $('input[name=\'filter_date\']').attr('value');
	
	if (filter_date) {
		url += '&filter_date=' + encodeURIComponent(filter_date);
	}
	
	var filter_city_from = $('input[name=\'filter_city_from\']').attr('value');
	
	if (filter_city_from) {
		url += '&filter_city_from=' + encodeURIComponent(filter_city_from);
	}
	
	var filter_city_to = $('input[name=\'filter_city_to\']').attr('value');
	
	if (filter_city_to) {
		url += '&filter_city_to=' + encodeURIComponent(filter_city_to);
	}
	
	var filter_status_id = $('select[name=\'filter_status_id\']').attr('value');
	
	if (filter_status_id != '*') {
		url += '&filter_status_id=' + encodeURIComponent(filter_status_id);
	}	

	var filter_total = $('input[name=\'filter_total\']').attr('value');

	if (filter_total) {
		url += '&filter_total=' + encodeURIComponent(filter_total);
	}
	
	location = url;
}

$(document).ready(function() {
	$('.date').datepicker({dateFormat: 'yy-mm-dd'});
});

//--></script> 
<?php echo $footer; ?>
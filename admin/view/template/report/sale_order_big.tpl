<?php echo $header; ?>
<div id="content">
	<div class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
			<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
		<?php } ?>
	</div>
	<div class="box">
		<div class="heading order_head">
			<h1><img src="view/image/report.png" alt="" /> <?php echo $heading_title; ?></h1>
		</div>
		<div class="content">
			<table class="form">
				<tr>
					<td><?php echo $entry_date_start; ?><br/>
					<input type="text" name="filter_year" value="<?php echo $filter_year; ?>" id="date-start" size="12" /></td>         
					<td style="text-align: right;"><a onclick="filter();" class="button"><?php echo $button_filter; ?></a></td>
				</tr>
			</table>
			<table class="list">
				<thead>
					<tr>
						<td class="left"><?php echo $column_date_start; ?></td>
						<td class="left"><?php echo $column_date_end; ?></td>
						<td class="right"><?php echo $column_orders; ?></td>
						<td class="right"><?php echo $column_products; ?></td>
						<td class="right"><?php echo $column_tax; ?></td>
						<td class="right"><?php echo $column_total; ?></td>
					</tr>
				</thead>
				<tbody>
					<?php if ($orders) { ?>
						<?php foreach ($orders as $order) { ?>
							<tr>
								<td class="left"><?php echo $order['date_start']; ?></td>
								<td class="left"><?php echo $order['date_end']; ?></td>
								<td class="right"><?php echo $order['orders']; ?></td>
								<td class="right"><?php echo $order['products']; ?></td>
								<td class="right"><?php echo $order['tax']; ?></td>
								<td class="right"><?php echo $order['total']; ?></td>
							</tr>
						<?php } ?>
						<?php } else { ?>
						<tr>
							<td class="center" colspan="6"><?php echo $text_no_results; ?></td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
			<div class="pagination"><?php echo $pagination; ?></div>
		</div>
	</div>
</div>
<script type="text/javascript"><!--
	function filter() {
		url = 'index.php?route=report/sale_order&token=<?php echo $token; ?>';
		
		var filter_date_start = $('input[name=\'filter_date_start\']').attr('value');
		
		if (filter_date_start) {
			url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
		}
		
		var filter_date_end = $('input[name=\'filter_date_end\']').attr('value');
		
		if (filter_date_end) {
			url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
		}
		
		var filter_group = $('select[name=\'filter_group\']').attr('value');
		
		if (filter_group) {
			url += '&filter_group=' + encodeURIComponent(filter_group);
		}
		
		var filter_order_status_id = $('select[name=\'filter_order_status_id\']').attr('value');
		
		if (filter_order_status_id != 0) {
			url += '&filter_order_status_id=' + encodeURIComponent(filter_order_status_id);
		}	
		
		location = url;
	}
//--></script> 
<script type="text/javascript"><!--
	$(document).ready(function() {
		$('#date-start').datepicker({dateFormat: 'yy-mm-dd'});
		
		$('#date-end').datepicker({dateFormat: 'yy-mm-dd'});
	});
//--></script> 
<?php echo $footer; ?>
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
					<td><input type="text" name="filter_date_start" placeholder="Отменен от" value="<?php echo $filter_date_start; ?>" size="12" class="date" /></td>
					<td><input type="text" name="filter_date_end" placeholder="Отменен до" value="<?php echo $filter_date_end; ?>" size="12" class="date" /></td>
					
					<td style="text-align: right;"><a onclick="filter();" class="button"><?php echo $button_filter; ?></a></td>
				</tr>
			</table>
			<table class="list">
				<tbody>
					<tr>
						<td class="left"><b>Причина отмены</b></td>
						<?php foreach ($order_stores as $order_store) { ?>
							<td class="left">
								<b><?php echo $order_store['name']; ?></b>
								<br />
								<span class="status_color" style="display:inline-block; padding:4px 2px; background: #00ad07; color:#fff; margin-top:4px;">Всего заказов: <?php echo $order_store['total_orders']; ?></span>
								
								<br />
								<span class="status_color" style="display:inline-block; padding:4px 2px; background: #ff7f00; color:#fff; margin-top:4px;">Всего отмен: <?php echo $order_store['total_cancelled']; ?></span>
								
								<br />
								<span class="status_color" style="display:inline-block; padding:4px 2px; background: #F96E64; color:#fff; margin-top:4px; ; font-weight:700">Процент отмен: <?php echo $order_store['percent_cancelled']; ?>%</span>
								
								
								<br />
								<span class="status_color" style="display:inline-block; padding:4px 2px; background: #00ad07; color:#fff; margin-top:4px;  white-space:nowrap; font-weight:700">Оформлено: <?php echo $order_store['sum_total_orders_txt']; ?></span>
								
								<br />
								<span class="status_color" style="display:inline-block; padding:4px 2px; background: #ff7f00; color:#fff; margin-top:4px; white-space:nowrap; font-weight:700">Отменено: <?php echo $order_store['sum_total_cancelled_txt']; ?></span>
								
								<br />
								<span class="status_color" style="display:inline-block; padding:4px 2px; background: #F96E64; color:#fff; margin-top:4px; white-space:nowrap; font-weight:700">Процент: <?php echo $order_store['sum_percent_cancelled']; ?>%</span>
							</td>
						<?php } ?>
					</tr>
				</tbody>
				<tbody>
					<?php if ($reject_reasons) { ?>
						<?php foreach ($reject_reasons as $reject_reason) { ?>
							<tr>
								<td class="left" style="width:300px;">
									<span style="display:inline-block;border-radius: 2px;padding: 10px 5px; background:#F96E64; color:white; font-weight:700">
										<?php echo $reject_reason['name']; ?>
									</span>
								</td>
								
								<?php unset($order_store); foreach ($order_stores as $order_store) { ?>
									<td class="left" style="whitespace:nowrap;">

										отмен: <b style="color:#ff5656"><?php echo $reject_reason['store_data'][$order_store['store_id']]['total_cancelled']; ?></b><br />								
										процент от всех: <b style="color:#ff5656"><?php echo $reject_reason['store_data'][$order_store['store_id']]['percent_cancelled']; ?>%</b><br />
										
										на сумму: <b style="color:#ff5656"><?php echo $reject_reason['store_data'][$order_store['store_id']]['sum_cancelled']; ?></b>

									</td>
								<?php } ?>
							</tr>
						<?php } ?>
						<?php } else { ?>
						<tr>
							<td class="center" colspan="6"><?php echo $text_no_results; ?></td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<script type="text/javascript"><!--
	function filter() {
		url = 'index.php?route=report/sale_reject&token=<?php echo $token; ?>';
		
		var filter_date_start = $('input[name=\'filter_date_start\']').attr('value');
		
		if (filter_date_start) {
			url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
		}
		
		var filter_date_end = $('input[name=\'filter_date_end\']').attr('value');
		
		if (filter_date_end) {
			url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
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
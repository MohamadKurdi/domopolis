<style>
	.admin_home{width:100%;background: #f3f3f3 url(/admin/view/image/bg_grey.png) repeat;}
	.admin_home tr td{text-align:center; padding-bottom:30px;}
	.admin_home tr td i{font-size:72px; color:#3a4247;}
	.admin_home tr td a{color:#3a4247;}
</style>
<table class="admin_home">
	<tr>
		<td>
            <a href="<?php echo $order_url; ?>">
                <i class="fa fa-shopping-bag"></i><br />
                Заказы
			</a>
		</td>
		<td>
			<a href="<?php echo $sale_return_url; ?>">
				<i class="fa fa-mail-reply-all"></i><br />
				Возвраты
			</a>
		</td>
		<td>
			<a href="<?php echo $waitlist_url ?>">
				<i class="fa fa-hourglass-2"></i><br />
				Лист ожидания
			</a>
		</td>
		
		<td>
			<a href="<?php echo $parties_url ?>">
				<i class="fa fa-list"></i><br />
				Партии закупок
			</a>
		</td>
	</tr>
</table>
<div style="clear:both"></div>
<div style="width:100%; margin-bottom:10px;">
	<div class="dashboard-heading">Проверка счетов покупателей<span style="display:inline-block; float:right; cursor:pointer" onclick="$('#minusscan').height(1200);"><span class="text_down">развернуть</span><i class="fa fa-chevron-down icon_down"></i></span></div>
	<div class="dashboard-content" id="minusscan" style="height:600px; overflow-y:scroll;" >
		<i class="fa fa-spinner fa-spin"></i>
	</div>
</div>		 
<script>
	$(document).ready(function(){								
		$('#minusscan').load('index.php?route=common/home/getMinusScanResult&token=<?php echo $token; ?>');	
	});
</script>
<div class="latest">
	<div class="dashboard-heading"><?php echo $text_latest_10_orders; ?></div>
	<div class="dashboard-content">
		<table class="list">
            <thead>
				<tr>
					<td class="right"><?php echo $column_order; ?></td>
					<td class="left"><?php echo $column_customer; ?></td>
					<td class="left"><?php echo $column_status; ?></td>
					<td class="left"><?php echo $column_date_added; ?></td>
					<td class="right"><?php echo $column_total; ?></td>
					<td class="right"><?php echo $column_action; ?></td>
				</tr>
			</thead>
            <tbody>
				<?php if ($orders) { ?>
					<?php foreach ($orders as $order) { ?>
						<tr>
							<td class="left"><?php echo $order['order_id']; ?></td>
							<td class="left"><?php echo $order['customer']; ?></td>
							<td class="left"><span class="status_color" style="background: #<?php echo isset($order['status_bg_color']) ? $order['status_bg_color'] : ''; ?>; color: #<?php echo isset($order['status_txt_color']) ? $order['status_txt_color'] : ''; ?>;"><?php echo $order['status']; ?></span></td>
							<td class="left"><?php echo $order['date_added']; ?></td>
							<td class="right"><?php echo $order['total']; ?></td>
							<td class="right"><?php foreach ($order['action'] as $action) { ?>
								<a href="<?php echo $action['href']; ?>"><i class="fa fa-folder-open-o"></i></a>
							<?php } ?></td>
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
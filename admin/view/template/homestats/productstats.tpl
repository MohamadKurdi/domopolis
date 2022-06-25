<style>
	.list.big thead td{font-size:14px; font-weight:700;}
	.list.big tbody td{font-size:18px;}
	.list.big tbody td.sum{font-size:11px;}
	.list.big tbody td.discount{font-size:11px;color:red;font-weight:700;}
	.list.big tbody td.percent{font-size:11px;color:#ff7815;font-weight:700;}
	.list.small-bottom-margin{margin-bottom: 10px;}
</style>


<div class="dashboard-heading">
	<i class="fa fa-ambulance"></i> Что происходит с контентом?			
</div>
<div class="dashboard-content" style="min-height:91px;">
			<table class="list big small-bottom-margin">
				<thead>
					<tr>
						<td colspan="4" class="left">
							Всего товаров
						</td>
					</tr>
					<tr>
						<td style="color:#66c7a3"><i class="fa fa-list" aria-hidden="true"></i> В базе</td>
						<td style="color:#fa4934"><i class="fa fa-check" aria-hidden="true"></i> Включено</td>
						<td style="color:#3276c2"><i class="fa fa-spinner" aria-hidden="true"></i> В тех.кат.</td>
						<td style="color:#24a4c1"><i class="fa fa-refresh" aria-hidden="true"></i> Получены</td>						
					</tr>
				</thead>
				<tr>
					<td><?php echo $total_products; ?></td>
					<td><?php echo $total_product_enabled; ?></td>
					<td><?php echo $total_products_in_tech; ?></td>
					<td><?php echo $total_product_parsed; ?></td>
				</tr>

			</table>

			<div style="clear:both; height:2px;"></div>

			<table class="list big small-bottom-margin">				
				<thead>
					<tr>
						<td colspan="3" class="left">
							Добавлено товаров
						</td>
					</tr>
					<tr>
						<td style="color:#66c7a3"><i class="fa fa-plus" aria-hidden="true"></i> Сегодня</td>
						<td style="color:#3276c2"><i class="fa fa-plus" aria-hidden="true"></i> Вчера</td>
						<td style="color:#24a4c1"><i class="fa fa-plus" aria-hidden="true"></i> Неделя</td>						
					</tr>
					<tr>
						<td><?php echo $total_products_added_today; ?></td>
						<td><?php echo $total_products_added_yesterday; ?></td>
						<td><?php echo $total_products_added_week; ?></td>
					</tr>
				</thead>

			</table>

			<div style="clear:both; height:2px;"></div>

			<table class="list big  small-bottom-margin">
				<thead>
					<tr>
						<td colspan="4" class="left">
							Категории
						</td>
					</tr>
					<tr>
						<td style="color:#66c7a3"><i class="fa fa-list" aria-hidden="true"></i> Всего</td>
						<td style="color:#3276c2"><i class="fa fa-amazon" aria-hidden="true"></i> Конечных</td>
						<td style="color:#24a4c1"><i class="fa fa-share" aria-hidden="true"></i> Синхрон</td>
						<td style="color:#fa4934"><i class="fa fa-refresh" aria-hidden="true"></i> Полные</td>
					</tr>
					<tr>
						<td><?php echo $total_categories; ?></td>
						<td><?php echo $total_categories_final; ?></td>
						<td><?php echo $total_categories_enable_load; ?></td>
						<td><?php echo $total_categories_enable_full_load; ?></td>
					</tr>
				</thead>

			</table>
</div>

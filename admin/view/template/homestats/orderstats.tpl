<style>
	.list.big thead td{font-size:14px; font-weight:700;}
	.list.big tbody td{font-size:18px;}
	.list.big tbody td.sum{font-size:11px;}
	.list.big tbody td.discount{font-size:11px;color:red;font-weight:700;}
	.list.big tbody td.percent{font-size:11px;color:#ff7815;font-weight:700;}
</style>
<?php foreach ($order_filters as $order_filter) { ?>
	<div style="width:48%; float:left; margin-bottom:15px; margin-left:5px;">
		<div class="dashboard-heading">
			<img src="view/image/flags/<? echo mb_strtolower($order_filter['language']) ?>.png" /> <?php echo $order_filter['name']; ?> <a href="<?php echo $order_filter['filter_href']; ?>" style="float:right; margin-right:10px;">посмотреть все</a>
		</div>
		<div class="dashboard-content" style="min-height:91px;">
			<table class="list big">
				<thead>
					<tr>
						<td>Сегодня</td>
						<td>Вчера</td>
						<td>Неделя</td>
						<td>Месяц</td>
						<td>Год</td>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><?php echo $order_filter['today_orders']['total']; ?></td>
						<td><?php echo $order_filter['yesterday_orders']['total']; ?></td>
						<td><?php echo $order_filter['week_orders']['total']; ?></td>
						<td><?php echo $order_filter['month_orders']['total']; ?></td>
						<td><?php echo $order_filter['year_orders']['total']; ?></td>
					</tr>
					<tr>
						<td class="sum"><?php echo $order_filter['today_orders']['sum']; ?></td>
						<td class="sum"><?php echo $order_filter['yesterday_orders']['sum']; ?></td>
						<td class="sum"><?php echo $order_filter['week_orders']['sum']; ?></td>
						<td class="sum"><?php echo $order_filter['month_orders']['sum']; ?></td>
						<td class="sum"><?php echo $order_filter['year_orders']['sum']; ?></td>
					</tr>
					<tr>
						<td class="discount"><?php echo $order_filter['today_orders']['discount']; ?></td>
						<td class="discount"><?php echo $order_filter['yesterday_orders']['discount']; ?></td>
						<td class="discount"><?php echo $order_filter['week_orders']['discount']; ?></td>
						<td class="discount"><?php echo $order_filter['month_orders']['discount']; ?></td>
						<td class="discount"><?php echo $order_filter['year_orders']['discount']; ?></td>
					</tr>
					<tr>
						<td class="percent"><?php echo $order_filter['today_orders']['percent']; ?>%</td>
						<td class="percent"><?php echo $order_filter['yesterday_orders']['percent']; ?>%</td>
						<td class="percent"><?php echo $order_filter['week_orders']['percent']; ?>%</td>
						<td class="percent"><?php echo $order_filter['month_orders']['percent']; ?>%</td>
						<td class="percent"><?php echo $order_filter['year_orders']['percent']; ?>%</td>
					</tr>
				
				</tbody>
			</table>	
			
			<div style="clear:both; height:2px;"></div>
			
			<table class="list big">
				<thead>
					<tr>
						<td style="color:#7F00FF"><i class="fa fa-cloud-download"></i> Установки PWA</td>
						<td>Сессии PWA/APP</td>
						<td>Заказы PWA/APP</td>
					</tr>
					<tr>
						<td><?php echo $fast_counters[$order_filter['store_id']]['pwainstall']; ?></td>
						<td><?php echo $fast_counters[$order_filter['store_id']]['pwasession']; ?></td>
						<td><?php echo $fast_counters[$order_filter['store_id']]['pwaorders']; ?></td>
					</tr>
				</thead>
			</table>
			
			<div style="clear:both; height:2px;"></div>
			
			<table class="list big">
				<thead>
					<tr>
						<td style="color:#02760e"><i class="fa fa-btc" aria-hidden="true"></i> Начислено</td>
						<td style="color:#ce1400"><i class="fa fa-btc" aria-hidden="true"></i> Списано</td>
						<td><i class="fa fa-btc" aria-hidden="true"></i> На счетах</td>
						<td>Заказов</td>
					</tr>
					<tr>
						<td><?php echo $fast_counters[$order_filter['store_id']]['rewardplus']; ?></td>
						<td><?php echo $fast_counters[$order_filter['store_id']]['rewardminus']; ?></td>
						<td><?php echo $fast_counters[$order_filter['store_id']]['rewardtotal']; ?></td>
						<td><?php echo $fast_counters[$order_filter['store_id']]['rewardorders']; ?></td>
					</tr>
				</thead>
			</table>
		</div>
	</div>	
<? } ?>
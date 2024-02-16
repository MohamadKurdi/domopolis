<style>
	.list.big thead td{font-size:14px; font-weight:400;}
	.list.big tbody td{font-size:18px;}
	.list.big tbody td.sum{font-size:11px;}
	.list.big tbody td.discount{font-size:11px;color:red;font-weight:400;}
	.list.big tbody td.percent{font-size:11px;color:#ff7815;font-weight:400;}
	.list tr.tr-orange td{background-color: #ff7815; color:#fff;}
	.list tr.tr-green td{background-color: #00ad07; color:#fff;}
</style>


<?php foreach ($order_filters as $order_filter) { ?>
	<div style="margin-bottom:15px;">
		<div class="dashboard-heading">
			<?php if ($this->config->get('config_admin_flags_enable')) { ?>
				<img src="<?php echo DIR_FLAGS_NAME; ?><? echo mb_strtolower($order_filter['language']) ?>.png" />
			<?php } ?>
			<?php echo $order_filter['name']; ?>
			<a href="<?php echo $order_filter['filter_href']; ?>" style="float:right; margin-right:10px;">посмотреть все</a>
		</div>
		<div class="dashboard-content" style="min-height:91px;">
			<div>
				<div style="width:50%; float:left;">	
					<table class="list big">
						<thead>
							<tr class="tr-orange">
								<td>Все сегодня</td>
								<td>Все вчера</td>
								<td>Все неделя</td>
								<td>Все месяц</td>
								<td>Все год</td>
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
				</div>
				<div style="width:50%; float:left;">	
					<table class="list big">
						<thead>
							<tr class="tr-green">
								<td>Ok сегодня</td>
								<td>Ok вчера</td>
								<td>Ok неделя</td>
								<td>Ok месяц</td>
								<td>Ok год</td>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><?php echo $order_filter['today_ok_orders']['total']; ?></td>
								<td><?php echo $order_filter['yesterday_ok_orders']['total']; ?></td>
								<td><?php echo $order_filter['week_ok_orders']['total']; ?></td>
								<td><?php echo $order_filter['month_ok_orders']['total']; ?></td>
								<td><?php echo $order_filter['year_ok_orders']['total']; ?></td>
							</tr>
							<tr>
								<td class="sum"><?php echo $order_filter['today_ok_orders']['sum']; ?></td>
								<td class="sum"><?php echo $order_filter['yesterday_ok_orders']['sum']; ?></td>
								<td class="sum"><?php echo $order_filter['week_ok_orders']['sum']; ?></td>
								<td class="sum"><?php echo $order_filter['month_ok_orders']['sum']; ?></td>
								<td class="sum"><?php echo $order_filter['year_ok_orders']['sum']; ?></td>
							</tr>
							<tr>
								<td class="discount"><?php echo $order_filter['today_ok_orders']['discount']; ?></td>
								<td class="discount"><?php echo $order_filter['yesterday_ok_orders']['discount']; ?></td>
								<td class="discount"><?php echo $order_filter['week_ok_orders']['discount']; ?></td>
								<td class="discount"><?php echo $order_filter['month_ok_orders']['discount']; ?></td>
								<td class="discount"><?php echo $order_filter['year_ok_orders']['discount']; ?></td>
							</tr>
							<tr>
								<td class="percent"><?php echo $order_filter['today_ok_orders']['percent']; ?>%</td>
								<td class="percent"><?php echo $order_filter['yesterday_ok_orders']['percent']; ?>%</td>
								<td class="percent"><?php echo $order_filter['week_ok_orders']['percent']; ?>%</td>
								<td class="percent"><?php echo $order_filter['month_ok_orders']['percent']; ?>%</td>
								<td class="percent"><?php echo $order_filter['year_ok_orders']['percent']; ?>%</td>
							</tr>
						</tbody>
					</table>	
				</div>
			</div>
			
			<div class="clr" style="height:2px;"></div>
			<div>
				<div style="width:50%; float:left;">	
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
				</div>
				<div style="width:50%; float:left;">						
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
				<div class="clr"></div>		
			</div>
		</div>
	</div>
<? } ?>
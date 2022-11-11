<style>
	table.address tr td{padding:1mm;border:0px;}
</style>

<style>
	table.table16 tr td {font-size:16px;padding-top:10px !important; padding-bottom:10px !important;}
	table.list {border-collapse: collapse}
	table.list tr td{padding:3px;border:1px solid black;border-collapse: collapse; background:#FFF;}
	table.list tr.heading td{text-align:center;}
	table.list tr td.left{text-align:left;}
	table.list tr td.right{text-align:right;}
	table.list tr td.center{text-align:center;}
	tr.lightblue td {background:#CCFFFF !important;}
	tr.lightgreen td {background:#CCFFCC !important;}
</style>		

<table style="width:100%">
	<tr>
		<td style="text-align:left; font-size:15px; padding-bottom:8px;border:0; word-wrap:none; white-space: nowrap;">
			<b>Отчет выполненной работы <? echo $user_name ?></b>
		</td>
	</tr>
	<tr>
		<td style="text-align:right; font-size:14px; padding-bottom:8px;border:0; word-wrap:none; white-space: nowrap;">
			<b>Учетный месяц: <? echo $dataname2; ?></b>
		</td>
	</tr>
	<tr>
		<td style="text-align:right; font-size:14px; padding-bottom:8px;border:0; word-wrap:none; white-space: nowrap;">
			<b>Дата формирования: <? echo date('d.m.Y'); ?></b>
		</td>
	</tr>
</table>
<div style="margin-top:10px;"> 
	<table class="list table16" style="width:100%">		
		
		<tr class="hovered">
			<td colspan="4" class="left"><b>Сводная таблица</b></td>																								
		</tr>
		
		<tr>
			<td class="left">
				Всего дней
			</td>
			<td class="center">
				<b><? echo $cstats['total_days']; ?></b>
			</td>
			<td class="left">
				Всего кликов
			</td>
			<td class="center">
				<b><? echo $cstats['total_actions']; ?></b>
			</td>
		</tr>
		
		<tr>
			<td class="left">
				Всего часов
			</td>
			<td class="center">
				<b><? echo $cstats['total_time']; ?></b>
			</td>
			<td class="left">
				Всего писем
			</td>
			<td class="center">
				<b><? echo $cstats['total_emails']; ?></b>
			</td>
		</tr>
		
		<tr>
			<td class="left">
				Всего входящих
			</td>
			<td class="center">
				<b><? echo $cstats['total_inbound']; ?><br />
					<? echo $cstats['total_inbound_duration']; ?>
				</b>
			</td>
			<td class="left">
				Всего исходящих
			</td>
			<td class="center">
				<b><? echo $cstats['total_outbound']; ?><br />
					<? echo $cstats['total_outbound_duration']; ?>
				</b>
			</td>
		</tr>
		
		<tr>
			<td class="left">
				Всего Дней Рождения
			</td>
			<td class="center">
				<b><? echo $cstats['total_bdays']; ?></b>
			</td>
			<td class="left">
				Всего CSI
			</td>
			<td class="center">
				<b><? echo $cstats['total_csi']; ?></b>
			</td>
		</tr>
		
		<tr>
			<td class="left">
				Всего оформлено заказов
			</td>
			<td class="center">
				<b><? echo $cstats['total_bad_orders']; ?></b>
			</td>
			<td class="left">
				Всего выполнено заказов
			</td>
			<td class="center">
				<b><? echo $cstats['total_good_orders']; ?></b>
			</td>
		</tr>
		
		
	</table>
</div>
<div style="margin-top:10px;"> 
	<table class="list" style="width:100%">		
		
		<? foreach ($orders_by_country as $country => &$orders) { ?>
			<tr>
				<td colspan="5">
					<b><? echo $country; ?></b>
				</td>
			</tr>
			<tr>
				<td class="left">
					<b>Заказ №</b>
				</td>
				<td class="left">
					<b>Промокод</b>
				</td>
				<td class="left">
					<b>Оформлен</b>
				</td>
				<td class="left">
					<b>Закрыт</b>
				</td>
				<td class="left">
					<b>Итог</b>
				</td>
			</tr>
			<? asort($orders); foreach ($orders as $order) { ?>
				<tr>
					<td class="left">
						<? echo $order['order_id'] ?>
					</td>
					<td class="left" nowrap style="white-space: nowrap">
						<? echo $order['promocode'] ?>
					</td>
					<td class="left">
						<? echo $order['date_added'] ?>
					</td>
					<td class="left">
						<? echo $order['date_closed'] ?>
					</td>
					<td class="left" nowrap style="white-space: nowrap">
						<? echo $order['total_text'] ?>
					</td>
				</tr>
			<? } ?>
		
		<tr>
			<td class="right" colspan="4">
				<b>Всего по <? echo $country; ?></b>
			</td>
			<td nowrap style="white-space: nowrap">	
				<b><? echo $cstats['total_good_sum'][$country]['text']; ?></b>
			</td>
		</tr>
	<? } ?>
		
	</table>
</div>
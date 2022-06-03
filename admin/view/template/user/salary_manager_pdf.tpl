<style>
	table.address tr td{padding:1mm;border:0px;}
</style>

<style>
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
			<? if ($is_headsales) { ?>
				<b>Отчет по закрытым заказам. РОП: <? echo $user_name ?></b>
				<? } else { ?>
				<b>Отчет по закрытым заказам. Менеджер: <? echo $user_name ?></b>
			<? } ?>
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
	<table class="list" style="width:100%">
		<tr class="hovered">
			<? if ($is_headsales) { ?>
				<td colspan="2" class="center"><b>Ключевые показатели эффективности руководителя отдела продаж</b></td>	
				<? } else { ?>
				<td colspan="2" class="center"><b>Ключевые показатели эффективности менеджера</b></td>	
			<? } ?>
		</tr>
		
		<tr>
			<td class="right">Процент неконверсионных заказов</td>
			<td class="right" style="font-size:18px; word-wrap:none; white-space: nowrap;"><b><? echo $cstats['kpi_params']['complete_cancel_percent']; ?></b></td>
		</tr>
		
		<tr>
			<td class="right">Среднее время подтверждения заказа, дней</td>
			<td class="right" style="font-size:18px; word-wrap:none; white-space: nowrap;"><b><? echo $cstats['kpi_params']['average_confirm_time']; ?></b></td>
		</tr>
		
		<tr>
			<td class="right">Среднее время выполнения заказа, дней</td>
			<td class="right" style="font-size:18px; word-wrap:none; white-space: nowrap;"><b><? echo $cstats['kpi_params']['average_process_time']; ?></b></td>
		</tr>
		
	</table>
</div>

<div style="margin-top:10px;"> 
	<table class="list" style="width:100%">
		<tr class="hovered">
			<td colspan="3" class="center"><b>Полный расчет зарплаты</b></td>																								
		</tr>
		
		<tr>
			<td colspan="2" class="right">Ставка</td>
			<td class="right" style="font-size:18px; word-wrap:none; white-space: nowrap;"><b><? echo $fixed_salary_txt; ?></b></td>
		</tr>
		
		<tr>
			<td colspan="2" class="right">Надбавка за выполнение параметров KPI составляет</td>
			<td class="right" style="font-size:18px; word-wrap:none; white-space: nowrap;"></td>
		</tr>
		
		<? if ($cstats['kpi_params']['complete_cancel_percent_value']) { ?>
			<tr>
				<td colspan="2" class="right">+ за процент конверсионных заказов <b><? echo $cstats['kpi_params']['complete_cancel_percent_percent']; ?>%</b> от общей суммы</td>
				<td class="right" style="font-size:18px; word-wrap:none; white-space: nowrap;">
					<b id="complete_cancel_percent_value_txt"><? echo $cstats['kpi_params']['complete_cancel_percent_value_txt']; ?></b>
				</td>
			</tr>	
		<? } ?>
		
		<? if ($cstats['kpi_params']['average_confirm_time_value']) { ?>
			<tr>
				<td colspan="2" class="right">+ за среднее время подтверждения заказов <b><? echo $cstats['kpi_params']['average_confirm_time_percent']; ?>%</b> от общей суммы</td>
				<td class="right" style="font-size:18px; word-wrap:none; white-space: nowrap;">
					<b id="average_confirm_time_value_txt"><? echo $cstats['kpi_params']['average_confirm_time_value_txt']; ?></b>
				</td>
			</tr>	
		<? } ?>
		
		<? if ($cstats['kpi_params']['average_process_time_value']) { ?>
			<tr>
				<td colspan="2" class="right">+ за среднее время полного выполнения заказов <b><? echo $cstats['kpi_params']['average_process_time_percent']; ?>%</b> от общей суммы</td>
				<td class="right" style="font-size:18px; word-wrap:none; white-space: nowrap;">
					<b id="average_process_time_value_txt"><? echo $cstats['kpi_params']['average_process_time_value_txt']; ?></b>
				</td>
			</tr>	
		<? } ?>
		
		<tr>
			<td colspan="2" class="right" style="font-size:24px; word-wrap:none; white-space: nowrap;">Итого</td>
			<td class="right" style="font-size:24px;"><span class="_green" id="full_salary_txt"><? echo $full_salary_txt; ?></span></td>
		</tr>
	</table>
</div>	

<? if (!$do_not_print_details) { ?>
	<div style="margin-top:10px;"> 
		<table class="list" style="width:100%">		
			
			<tr class="hovered">
				<td colspan="10" class="center"><b>Детализация по заказам</b></td>																								
			</tr>
			
			<? foreach ($country_orders as $store) { ?>
				<tr class="lightgreen" style="background-color:#ccc;">
					<td colspan="8" class="left"><b><? echo $store['country_name']?></b></td>																								
				</tr>
				
				<tr>				
					<td class="left">
						<b>Код</b>
					</td>
					<td class="left">
						<b>Оформлен</b>
					</td>
					<td class="left">
						<b>Подтвержден</b>
					</td>
					<td class="left">
						<b>Закрыт</b>
					</td>
					<td class="left">
						<b>Всего по заказу</b>
					</td>
					<td class="left">
						<b>Прошлые партии<? if ($filter_count_time) { ?> / штраф<? } ?></b>
					</td>		
					<td class="left">
						<b>Зарплата выдана</b>
					</td>
					<td class="left">
						<b>К выплате по заказу</b>
					</td>
				</tr>
				
				<? foreach ($store['orders'] as $order) { ?>
					<tr>
						<td class="left" style="word-wrap:none; white-space: nowrap;">
							<? echo $order['order_id'] ?>&nbsp;<a href="<? echo $order['url'] ?>" target="_blank"><i class="fa fa-external-link" aria-hidden="true"></i></a>
						</td>
						<td class="left" style="word-wrap:none; white-space: nowrap;">
							<? echo $order['date_added'] ?>
						</td>
						<td class="left" style="word-wrap:none; white-space: nowrap;">
							<div><? echo $order['date_aссepted'] ?></div>
							<div><span style="font-size:10px; font-weight:700"><? echo $order['date_accepted_diff'] ?> дн.</span></div>
						</td>
						<td class="left" style="word-wrap:none; white-space: nowrap;">
							<div><? echo $order['date_closed'] ?></div>
							<div><span style="font-size:10px; font-weight:700"><? echo $order['date_closed_diff'] ?> дн.</span></div>
						</td>
						<td class="left" style="word-wrap:none; white-space: nowrap;">
							<? echo $order['total_text'] ?>
						</td>
						<td class="left" style="word-wrap:none; white-space: nowrap;">
							<? echo $order['already_paid_text'] ?>
						</td>
						<td class="left" style="word-wrap:none; white-space: nowrap;">
							<? if ($order['salary_paid']) { ?>
								Да
								<? } else { ?>
								Нет
							<? } ?>
						</td>
						<td class="left" style="word-wrap:none; white-space: nowrap;">
							<? echo $order['left_to_pay_text'] ?>
						</td>
					</tr>
				<? } ?>
				<tr class="lightblue">
					<td class="right" colspan="4">
						<b>Всего по <? echo $store['country_name']?></b>
					</td>						
					<td class="left" style="word-wrap:none; white-space: nowrap;">
						<b>Всего</b>
					</td>
					<td class="left" style="word-wrap:none; white-space: nowrap;">
						<b>Уже выплачено</b>
					</td>							
					<td class="left" style="word-wrap:none; white-space: nowrap;">
						<b>К выплате</b>
					</td>
				</tr>
				<tr>
					<td class="right" colspan="4">								
					</td>
					<td class="center" style="word-wrap:none; white-space: nowrap;">
						<div style="display:inline-block; padding:3px 5px;font-size:14px; font-weight:700;"><b><? echo $cstats['total_good_sum'][$store['country_name']]['total_text']; ?></b></div>
					</td>
					<td class="center" style="word-wrap:none; white-space: nowrap;">
						<div style="display:inline-block; padding:3px 5px;  font-size:14px; font-weight:700;"><b><? echo $cstats['total_good_sum'][$store['country_name']]['already_paid_text']; ?></b></div>
					</td>
					<td class="center" style="word-wrap:none; white-space: nowrap;">
						<div style="display:inline-block; padding:3px 5px;font-size:14px; font-weight:700;"><b><? echo $cstats['total_good_sum'][$store['country_name']]['left_to_pay_text']; ?></b></div>
					</td>
				</tr>
			<? } ?>
			
		</table>
	</div>
<? } ?>
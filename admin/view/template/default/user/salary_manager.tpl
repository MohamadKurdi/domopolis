<?php echo $header; ?>
<style type="text/css">
	table.ordermain,table.history{width:100%;border-collapse:collapse;margin-bottom:20px}
	table.orderadress,table.orderproduct{width:48%;border-collapse:collapse;margin-bottom:20px}
	table.ordermain > tbody > tr > th,table.orderadress > tbody > tr > th,table.list > thead > tr > th,table.history > tbody > tr > th,table.form > tbody > tr > th{padding:5px 0; color: #FFF;font-size: 14px;font-weight: 400;margin-bottom:5px;}
	table.ordermain > tbody > tr > td{width:25%}
	table.ordermain > tbody > tr > td:nth-child(odd),table.orderadress > tbody > tr > td:nth-child(odd){background:#EFEFEF}
	table.ordermain > tbody > tr > td,table.orderadress > tbody > tr > td{padding:5px;color:#696969;border-bottom:1px dotted #CCC}
	.clr{clear:both}
	input[type="text"]{width:70%;}
	input.onfocusedit_direct, textarea.onfocusedit_direct, textarea.onfocusout_edit_history,  select.onchangeedit_direct, input.onchangeedit_direct, textarea.onfocusedit_source, textarea.onfocusedit_customer{border-left-color:#4ea24e;}
	
	input.onfocusedit_direct.done, textarea.onfocusedit_direct.done, textarea.onfocusout_edit_history.done, select.onchangeedit_direct.done, input.onchangeedit_direct.done, textarea.onfocusedit_source.done, textarea.onfocusedit_customer.done{border-color:#4ea24e;-webkit-transition : border 500ms ease-out;-moz-transition : border 500ms ease-out; -o-transition : border 500ms ease-out;transition : border 500ms ease-out;}
	
	input.onfocusedit_direct.done+span:after, textarea.onfocusedit_direct.done+span:after, textarea.onfocusout_edit_history.done+span:after, select.onchangeedit_direct.done+span:after, textarea.onfocusedit_source.done+span:after,.onchangeedit_orderproduct.done+label+span:after, textarea.onfocusedit_customer.done+span:after{padding:2px;width:20px;height:20px;display:inline-block;font-family:FontAwesome;font-size:20px;color:#4ea24e;content:"\f00c"}
	
	input.onfocusedit_direct.loading+span:after, textarea.onfocusedit_direct.loading+span:after, textarea.onfocusout_edit_history.loading+span:after, select.onchangeedit_direct.loading+span:after, textarea.onfocusedit_source.loading+span:after,.onchangeedit_orderproduct.loading+label+span:after,textarea.onfocusedit_customer.loading+span:after{padding:2px;width:20px;height:20px;display:inline-block;font-family:FontAwesome;font-size:20px;color:#e4c25a;content:"\f021"}
	
	input.onfocusedit_direct.error+span:after, textarea.onfocusedit_direct.error+span:after, textarea.onfocusout_edit_history.error+span:after, select.onchangeedit_direct.error+span:after, textarea.onfocusedit_source.error+span:after, .onchangeedit_orderproduct.error+label+span:after,textarea.onfocusedit_customer.error+span:after{padding:2px;width:20px;height:20px;display:inline-block;font-family:FontAwesome;font-size:20px;color:#cf4a61;content:"\f071"}
</style>
<style>
	table.table16 tr td {font-size:16px;padding-top:10px !important; padding-bottom:10px !important;}
	tr.hovered td {background-color:#faf9f1 !important;}
	tr.blue:hover td {background:#99CCFF !important; color:#FFF !important;}
	tr.lightblue td {background:#CCFFFF !important;}
	tr.lightgreen td {background:#CCFFCC !important;}
	td.td_alert {background:#FF99CC !important;}
	span._red{display:inline-block; padding:3px 5px; background:#FF8080; color:#fff;}
	span._orange{display:inline-block; padding:3px 5px; background:#F93; color:#fff;}
	span._green{display:inline-block; padding:3px 5px; background:#CCFFCC; }
</style>
<div id="content">
	<div class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
			<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
		<?php } ?>
	</div>
	<div class="box">
		<div class="heading order_head">
			<h1>Отчет по закрытым заказам - менеджера</h1>			
			<? if ($allow_to_view_sums) { ?>
				<div style="float:right">	
					
					<a onclick="filterxls();" class="button"><i class="fa fa-file-excel-o"></i> XLS детально заказы</a>&nbsp;&nbsp;
					<a onclick="filterxlssimple();" class="button"><i class="fa fa-file-excel-o"></i> XLS общий оборот</a>
					
				</div>		
			<? } ?>
		</div>
		
		<div class="clear:both"></div>
		<div class="content" style="padding:5px;">
			<div> 				
				<table style="width: 100%;">
					<tbody>
						<tr class="filter f_top">
							<td>
								<p><i class="fa fa-calendar" aria-hidden="true"></i>Месяц</p>
								<select name="filter_month">
									<? setlocale(LC_TIME, 'ru_RU.UTF-8'); ?>
									<? for ($i=1; $i<=12; $i++) { $c = (date('m') - $i); var_dump($c); ?>										
										<? if ($filter_month == $i) { ?>
											<option value="<? echo $i; ?>" selected="selected"><? echo $month[$i-1]; ?></option>
											<? } else { ?>
											<option value="<? echo $i; ?>"><? echo $month[$i-1]; ?></option>
										<? } ?>
									<? } ?>
								</select>
							</td>
							<td>
								<p><i class="fa fa-calendar" aria-hidden="true"></i>Год</p>
								<select name="filter_year">
									<? for ($i=date('Y', strtotime("-2 year")); $i<=date('Y'); $i++) { ?>										
										<? if ($filter_year == $i) { ?>
											<option value="<? echo $i; ?>" selected="selected"><? echo $i; ?></option>
											<? } else { ?>
											<option value="<? echo $i; ?>"><? echo $i; ?></option>
										<? } ?>
									<? } ?>
								</select>
							</td>
							
							<td>
								<p><i class="fa fa-user"></i> Менеджер</p>
								<select name="filter_manager">									
									<?php foreach ($managers as $manager) { ?>
										<?php if ($manager['user_id'] == $filter_manager) { ?>
											<option value="<?php echo $manager['user_id'] ?>" selected="selected"><? if ($manager['is_headsales']) { ?>Руководитель: <? } ?> <?php echo $manager['realname']; ?></option>
											<?php } else { ?>
											<option value="<?php echo $manager['user_id'] ?>"><? if ($manager['is_headsales']) { ?>Руководитель: <? } ?> <?php echo $manager['realname']; ?></option>
										<?php } ?>
									<?php } ?>
								</select>
							</td>
							
							<td colspan="" align="left">
								<div style="display:none;">
									<input type="checkbox" class="checkbox" name="filter_dzioev_bug" id="filter_dzioev_bug" />
									<label for="filter_dzioev_bug"><b>Учесть передачу заказов Джиоева Головку</b></label>
									<span class="help">в оплату входит половина суммы оставшейся оплаты</span>
								</div>
								<div style="display:none;">
									<input type="checkbox" class="checkbox" name="filter_count_time" id="filter_count_time" <? if ($filter_count_time) { ?>checked="checked"<? } ?> />
									<label for="filter_count_time"><b>Штраф за превышение времени выполнения</b></label>
								</div>
								<input type="hidden" id="filter_count_time_days" name="filter_count_time_days" value="<? echo $filter_count_time_days; ?>" />
								<div style="margin-top:10px; max-width:300px; display:none;" id="slider">
									<div id="custom-handle" class="ui-slider-handle"></div>
								</div>
								<style>
									#custom-handle {
									width: 3em;
									height: 1.6em;
									top: 50%;
									margin-top: -.9em;
									text-align: center;
									line-height: 1.6em;
									padding:3px;
									border-radius: 5px;
									}
									.ui-widget-content{
									border: 1px solid #FF8080;
									border-radius: 5px;
									padding:0px;
									}
									.ui-state-default, .ui-widget-content .ui-state-default, .ui-widget-header .ui-state-default{
									border: 1px solid #FF8080;
									font-weight: bold;
									color: #fff;
									background: #FF8080;
									}
								</style>
								<script>
									$( function() {
										var handle = $( "#custom-handle" );
										var filter_count_time_days_input = $( "#filter_count_time_days" );
										$( "#slider" ).slider({
											value: <? echo $filter_count_time_days; ?>,
											min: 20,
											max: 60,
											step: 1,
											create: function() {
												handle.text( $( this ).slider( "value" ) + ' дн.'  );
											},
											slide: function( event, ui ) {
												handle.text( ui.value + ' дн.' );
												filter_count_time_days_input.val(ui.value);
											}
										});
									} );
								</script>
							</td>
							
							<td colspan="" align="right">
								<p>	&#160;</p>
								<a onclick="filter();" class="button">Сформировать</a>
							</td>
							<td colspan="" align="right">	
								<? if ($allow_to_view_sums) { ?>
									<p>	&#160;</p>
									<a onclick="filterpdf(true);" class="button"><i class="fa fa-file-pdf-o"></i> PDF</a>
									<a onclick="filterpdf(false);" class="button"><i class="fa fa-file-pdf-o"></i> PDF без расшифровки</a>
								<? } ?>
							</td>						
						</tr>
					</tbody>
				</table>
			</div>
			<div style="margin-top:10px;">
				<? if ($allow_to_view_sums) { ?>
					<style>
						tr.hideme{display:none;}
					</style>
				<? } ?>
				<table class="list table16">							
					<tr class="hovered">
						<td colspan="9" class="center"><b>Сводная таблица</b> <span style="float:right; cursor:pointer; border-bottom:1px dashed #696969;" onclick="$('.hideme').toggle();">показать больше</span></td>																					
					</tr>
					
					<tr class="hideme">
						<td class="left">
							<i class="fa fa-clock-o"  aria-hidden="true"></i>&nbsp;Всего дней
						</td>
						<td class="center" >
							<b><? echo $cstats['total_days']; ?></b>
						</td>
						<td class="left" >
							<i class="fa fa-refresh"  aria-hidden="true"></i>&nbsp;Всего кликов
						</td>
						<td class="center" >
							<b><? echo $cstats['total_actions']; ?></b>
						</td>
					</tr>
					
					<tr class="hideme">
						<td class="left">
							<i class="fa fa-clock-o"  aria-hidden="true"></i>&nbsp;Всего часов
						</td>
						<td class="center" >
							<b><? echo $cstats['total_time']; ?></b>
						</td>
						<td class="left" >
							<i class="fa fa-envelope"  aria-hidden="true"></i>&nbsp;Всего писем
						</td>
						<td class="center" >
							<b><? echo $cstats['total_emails']; ?></b>
						</td>
					</tr>
					
					<tr class="hideme">
						<td class="left">
							<i class="fa fa-phone" aria-hidden="true"></i>&nbsp;Всего входящих
						</td>
						<td class="center" >
							<b><? echo $cstats['total_inbound']; ?><br />
								<? echo $cstats['total_inbound_duration']; ?>
							</b>
						</td>
						<td class="left" >
							<i class="fa fa-phone" aria-hidden="true"></i>&nbsp;Всего исходящих
						</td>
						<td class="center" >
							<b><? echo $cstats['total_outbound']; ?><br />
								<? echo $cstats['total_outbound_duration']; ?>
							</b>
						</td>
					</tr>
					
					<tr class="hovered hideme">
						<? if ($is_headsales) { ?>
							<td colspan="9" class="center"><b>Работа отдела продаж</b></td>	
						<? } else { ?>
							<td colspan="9" class="center"><b>Работа менеджера</b></td>	
						<? } ?>
					</tr>
					
					<tr class="hideme">
						<td class="left">
							<i class="fa fa-cart-arrow-down" aria-hidden="true"></i>&nbsp;Всего отредактировано заказов
						</td>
						<td class="center" >
							<b><? echo $cstats['total_edit_order_count']; ?></b>
						</td>
						<td class="left" >
							<i class="fa fa-users"></i>&nbsp;Всего отредактировано покупателей
						</td>
						<td class="center" >
							<b><? echo $cstats['total_edit_customer_count']; ?></b>
						</td>
					</tr>
					
					<tr class="hideme">
						<td class="left">
							<? if ($is_headsales) { ?>
								<i class="fa fa-handshake-o"></i>&nbsp;Средний CSI по заказам
							<? } else { ?>
								<i class="fa fa-handshake-o"></i>&nbsp;Средний CSI по заказам
							<? } ?>
						</td>
						<td class="center" >
							<div style="display:inline-block;" id="avg_csi_by_orders"></div><br />
							<b><? echo round($cstats['avg_csi_by_orders'], 2); ?></b>
						</td>
						<td class="left">
							<? if ($is_headsales) { ?>
								<i class="fa fa-handshake-o"></i>&nbsp;Средний CSI по отделу продаж
							<? } else { ?>
								<i class="fa fa-handshake-o"></i>&nbsp;Средний CSI по менеджеру
							<? } ?>
						</td>
						<td class="center">
							<div style="display:inline-block;" id="avg_csi_by_manager"></div><br />
							<b><? echo round($cstats['avg_csi_by_manager'],2); ?></b>
						</td>
					</tr>
					
					<tr class="hovered hideme">
						<? if ($is_headsales) { ?>
							<td colspan="9" class="center"><b>Эффективность отдела продаж</b></td>		
						<? } else { ?>
							<td colspan="9" class="center"><b>Эффективность менеджера</b></td>		
						<? } ?>
					</tr>
					
					<tr class="hideme">
						<td class="left">
							<i class="fa fa-cart-arrow-down"></i>&nbsp;Всего присвоено
						</td>
						<td class="center" >
							<span class="_green"><b><? echo $cstats['total_owned_order_count']; ?></b></span> <a href="<? echo $cstats['total_owned_order_filter']; ?>" target="_blank"><i class="fa fa-filter" aria-hidden="true"></i></a>
							<br />
							<span class="help"><i class="fa fa-info-circle" aria-hidden="true"></i> количество заказов, оформленных за период и присвоенных менеджеру, берется как 100% в подсчете индикатора KPI ПНЗМ</span>												
						</td>
						<td class="left" >
							<? if ($is_headsales) { ?>
								<i class="fa fa-thumbs-up" aria-hidden="true"></i>&nbsp;Всего подтверждено
							<? } else { ?>
								<i class="fa fa-thumbs-up" aria-hidden="true"></i>&nbsp;Всего подтвердил
							<? } ?>
						</td>
						<td class="center" >
							<span class="_green"><b><? echo $cstats['total_confirmed_order_count']; ?></b></span>
							<br />
							<? if ($is_headsales) { ?>
								<span class="help"><i class="fa fa-info-circle" aria-hidden="true"></i> количество установок подтверждающих всем отделом статусов, вне зависимости от даты оформления</span>
							<? } else { ?>
								<span class="help"><i class="fa fa-info-circle" aria-hidden="true"></i> количество установок подтверждающих статусов, вне зависимости от даты оформления</span>
							<? } ?>
						</td>
					</tr>
					
					<tr class="hideme">
						<td class="left">
							<i class="fa fa-cart-arrow-down"></i>&nbsp;Всего конверсионных
						</td>
						<td class="center" >
							<span class="_green"><b><? echo $cstats['total_confirmed_to_process_order_count']; ?></b></span>
							<br />
							<? if ($is_headsales) { ?>
								<span class="help"><i class="fa fa-info-circle" aria-hidden="true"></i> количество заказов, оформленных за период и запущенных в выполнение либо полностью оплаченных, берется как процент в подсчете индикатора KPI ПНЗМ</span>
							<? } else { ?>		
								<span class="help"><i class="fa fa-info-circle" aria-hidden="true"></i> количество заказов, оформленных за период всем отделом и запущенных в выполнение либо полностью оплаченных, берется как процент в подсчете индикатора KPI ПНЗМ</span>
							<? } ?>
						</td>
						<td class="left" >							
						</td>
						<td class="center" >							
						</td>
					</tr>
					
					<tr class="hideme">				
						<td class="left">
							<? if ($is_headsales) { ?>
								<i class="fa fa-window-close" aria-hidden="true"></i>&nbsp;Всего отменено	
							<? } else { ?>
								<i class="fa fa-window-close" aria-hidden="true"></i>&nbsp;Всего отменил	
							<? } ?>
						</td>
						
						<td class="center" >
							<span class="_red"><b><? echo $cstats['total_cancel_order_count2']; ?></b></span>	
							<br />
							<? if ($is_headsales) { ?>
								<span class="help"><i class="fa fa-info-circle" aria-hidden="true"></i> количество отменененных менеджером заказов за период вне зависимости от даты оформления</span>
							<? } else { ?>
								<span class="help"><i class="fa fa-info-circle" aria-hidden="true"></i> количество отменененных менеджером заказов за период вне зависимости от даты оформления</span>
							<? } ?>
						</td>
						
						<td class="left" >
							<? if ($is_headsales) { ?>
								<i class="fa fa-shopping-basket" aria-hidden="true"></i>&nbsp;Всего закрыто
							<? } else { ?>
								<i class="fa fa-shopping-basket" aria-hidden="true"></i>&nbsp;Всего закрыл успешно
							<? } ?>
						</td>
						
						<td class="center" >
							<span class="_green"><b><? echo $cstats['total_success_order_count2']; ?></b></span>
							<br />
							<? if ($is_headsales) { ?>
								<span class="help"><i class="fa fa-info-circle" aria-hidden="true"></i> количество закрытых выполнением отделом продаж заказов за период вне зависимости от даты оформления</span>
							<? } else { ?>
								<span class="help"><i class="fa fa-info-circle" aria-hidden="true"></i> количество закрытых выполнением менеджером заказов за период вне зависимости от даты оформления</span>							
							<? } ?>
						</td>
						
					</tr>
				</table>	
				
				<? if ($allow_to_view_sums) { ?>	
					<table class="list table16">	
						<tr class="lightblue">
							<td class="left" colspan="1">							
							</td>							
							<td class="right">
								<b>Страна</b>
							</td>
							<td class="center">
								<b>Валюта</b>
							</td>
							<td class="center">
								<b>Курс к UAH</b>
								<br />
								<span class="help">внутр. магазина</span>
							</td>
							<td class="center">
								<b>Курс к UAH</b>
								<br />
								<span class="help">для просчетов</span>
								</td>
								<td class="center">
								<b>Полная сумма</b>
							</td>
							<td class="center">
								<b>Выплачено</b><br />
								<b>или штраф</b>
							</td>
							<td class="center">
								<b>К оплате</b>
							</td>
							<td class="center">
								<b>К оплате, грн</b>
							</td>
						</tr>
						<? foreach ($cstats['total_good_sum'] as $country => $sum) { ?>						
							<tr>
								<td class="left" colspan="1">							
								</td>							
								<td class="right">
									<b><? echo $country; ?></b>
								</td>
								<td class="center">	
									<b><? echo $sum['currency']; ?></b>
								</td>							
								<td class="center">	
									<b><? echo $sum['course']; ?></b>
								</td>
								<td class="center" width="140px" style="width:140px;">
									<b><input type="text" length="5" style="width:70px;" class="currency_uah_value onchangeedit_direct" value="<? echo $sum['course_real']; ?>" data-currency-id="<? echo $sum['currency']; ?>" />
										<i class="fa fa-check fake_clicker" style="display:none;"></i>
									</b>
								</td>
								<td class="center">
									<div style="display:inline-block; padding:3px 5px; background:#FF8080; color:#fff; font-size:14px; font-weight:700;"><b><? echo $sum['total_text']; ?></b></div>
								</td>
								<td class="center">
									<div style="display:inline-block; padding:3px 5px; background:#FF8080; color:#fff; font-size:14px; font-weight:700;"><b><? echo $sum['already_paid_text']; ?></b></div>
								</td>
								<td class="center">
									<div style="display:inline-block; padding:3px 5px; background:#FF8080; color:#fff; font-size:14px; font-weight:700;"><b><? echo $sum['left_to_pay_text']; ?></b></div>							
								</td>
								<td class="center">
									<div style="display:inline-block; padding:3px 5px; background:#FF8080; color:#fff; font-size:14px; font-weight:700;"><b id="<? echo $sum['currency'] ?>_left_to_pay_text_uah"><? echo $sum['left_to_pay_text_uah']; ?></b></div>
								</td>
							</tr>
						<? } ?>
						<tr>
							<td class="right" colspan="8">
								<b>Всего закрыто заказов на сумму</b> 
							</td>														
							<td class="center">
								<div style="display:inline-block; padding:3px 5px; background:#FF8080; color:#fff; font-size:14px; font-weight:700;"><b id="das_total_in_uah"><? echo $das_total_in_uah; ?></b></div>
							</td>
						</tr>
						
						<? if ($has_rights && !$is_headsales) { ?>
							
							<tr>							
								<td colspan="6">							
								</td>
								<td colspan="3" class="right">
									<input type="checkbox" class="checkbox" id="close_month" />
									<label for="close_month"></label>
									
									<a class="button redbg" id="button_do_close_month" onclick="" style="">Фиксировать оплату всех заказов</a>
								</td>						
							</tr>
							
						<? } ?>
					<? } ?>
					
				</table>
			</div>
			
			<div style="margin-top:10px;"> 
				<table class="list table16">							
					<tr class="hovered">
						<? if ($is_headsales) { ?>
							<td colspan="3" class="left center"><b>Параметры KPI (ключевые показатели Руководителя Отдела Продаж)</b></td>																								
							<? } else { ?>
							<td colspan="3" class="left center"><b>Параметры KPI (ключевые показатели эффективности менеджера)</b></td>
						<? } ?>
					</tr>
					<tr>
						<td class="center">
							<b>Процент неконверсионных заказов</b><br />
							<span class='help'>зоны: <? echo str_replace('10000000', ' 100 ', implode(' <= ', $cstats['kpi_params']['complete_cancel_percent_params'])); ?></span>
						</td>
						<td class="center">
							<b>Среднее время подтверждения заказа, дней</b><br />
							<span class='help'>зоны: <? echo str_replace('10000000', ' Макс ', implode(' <= ', $cstats['kpi_params']['average_confirm_time_params'])); ?></span>
						</td>
						<td class="center">
							<b>Среднее время выполнения заказа, дней</b><br />
							<span class='help'>зоны: <? echo str_replace('10000000', ' Макс ', implode(' <= ', $cstats['kpi_params']['average_process_time_params'])); ?></span>
						</td>
					</tr>
					<tr>
						<td class="center" style="font-size:18px;">
							<span class="<? echo $cstats['kpi_params']['complete_cancel_percent_clr']; ?>"><b><? echo $cstats['kpi_params']['complete_cancel_percent']; ?></b></span>
						</td>
						<td class="center" style="font-size:18px;">	
							<span class="<? echo $cstats['kpi_params']['average_confirm_time_clr']; ?>"><b><? echo $cstats['kpi_params']['average_confirm_time']; ?></b></span>
						</td>
						
						<td class="center" style="font-size:18px;">
							<span class="<? echo $cstats['kpi_params']['average_process_time_clr']; ?>"><b><? echo $cstats['kpi_params']['average_process_time']; ?></b></span>
						</td>
					</tr>	
					
					<? if ((int)$das_total_in_uah_num > 0) { ?>
						<tr class="hovered">
							<td colspan="3" class="left center"><b>Полный расчет зарплаты</b></td>																								
						</tr>
						
						<tr>
							<td colspan="2" class="right">Ставка</td>
							<td class="right" style="font-size:18px;"><b><? echo $fixed_salary_txt; ?></b></td>
						</tr>
						
						<tr>
							<td colspan="2" class="right">Надбавка за выполнение параметров KPI составляет</td>
							<td class="right" style="font-size:18px;"></td>
						</tr>
						
						<? if ($cstats['kpi_params']['complete_cancel_percent_value']) { ?>
							<tr>
								<td colspan="2" class="right">+ за процент конверсионных заказов <b><? echo $cstats['kpi_params']['complete_cancel_percent_percent']; ?>%</b> от общей суммы</td>
								<td class="right" style="font-size:18px;">
									<? if ($allow_to_view_sums) { ?><b id="complete_cancel_percent_value_txt"><? echo $cstats['kpi_params']['complete_cancel_percent_value_txt']; ?></b><? } ?>
								</td>
							</tr>	
						<? } ?>
						
						<? if ($cstats['kpi_params']['average_confirm_time_value']) { ?>
							<tr>
								<td colspan="2" class="right">+ за среднее время подтверждения заказов <b><? echo $cstats['kpi_params']['average_confirm_time_percent']; ?>%</b> от общей суммы</td>
								<td class="right" style="font-size:18px;">
									<? if ($allow_to_view_sums) { ?><b id="average_confirm_time_value_txt"><? echo $cstats['kpi_params']['average_confirm_time_value_txt']; ?></b><? } ?>
								</td>
							</tr>	
						<? } ?>
						
						<? if ($cstats['kpi_params']['average_process_time_value']) { ?>
							<tr>
								<td colspan="2" class="right">+ за среднее время полного выполнения заказов <b><? echo $cstats['kpi_params']['average_process_time_percent']; ?>%</b> от общей суммы</td>
								<td class="right" style="font-size:18px;">
									<? if ($allow_to_view_sums) { ?><b id="average_process_time_value_txt"><? echo $cstats['kpi_params']['average_process_time_value_txt']; ?></b><? } ?>
								</td>
							</tr>	
						<? } ?>
						<? if ($allow_to_view_sums) { ?>
							<tr>
								<td colspan="2" class="right" style="font-size:24px;">Итого</td>
								<td class="right" style="font-size:24px;"><span class="_green" id="full_salary_txt"><? echo $full_salary_txt; ?></span></td>
							</tr>
						<? } ?>
						<? } else { ?>		
						<tr class="hovered">
							<td colspan="3" class="left center"><b>Все заказы закрыты и оплачены</b></td>																								
						</tr>
					<? } ?>
					
				</table>
			</div>
			
			
			<div style="margin-top:10px;"> 
				<table class="list">		
					
					<tr class="hovered">
						<td colspan="10" class="left"><b>Детализация по заказам</b></td>																								
					</tr>
					
					<? foreach ($country_orders as $store) { ?>
						<tr class="lightgreen">
							<td colspan="11" class="left"><b><? echo $store['country_name']?></b></td>																								
						</tr>
						
						<tr>
							<td class="left">
								<b>Оплачен</b>
							</td>							
							<td class="left">
								<b>Код</b>
							</td>
							<td class="left">
								<b>Менеджер</b>
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
								<b>Проблемы</b>
							</td>
							<td class="left">
								<b>Инфо</b>
							</td>
							<td class="left">
								<b>Всего по заказу</b>
							</td>
							<td class="left">
								<b>Уже выплачено<? if ($filter_count_time) { ?> / штраф<? } ?></b>
							</td>							
							<td class="left">
								<b>К выплате по заказу</b>
							</td>
						</tr>
						
						<? foreach ($store['orders'] as $order) { ?>
							<tr>
								<td class="left">
									<input data-order-id="<? echo $order['order_id'] ?>" type="checkbox" class="checkbox salary_checkbox" <? if (!$has_rights) { ?>disabled="disabled"<? } ?> id="salary_<? echo $order['order_id'] ?>" <? if ($order['salary_paid']) { ?>checked="checked"<? } ?> />
									<label class="salary_checkbox" for="salary_<? echo $order['order_id'] ?>"></label>
								</td>
								<td class="left">
									<? echo $order['order_id'] ?>&nbsp;<a href="<? echo $order['url'] ?>" target="_blank"><i class="fa fa-external-link" aria-hidden="true"></i></a>
								</td>
								<td class="left">
									<? echo $order['manager_name']; ?>
								</td>
								<td class="left">
									<? echo $order['date_added'] ?>									
								</td>
								<td class="left">
									<div><? echo $order['date_aссepted'] ?></div>
									<div><span style="font-size:10px; font-weight:700"><? echo $order['date_accepted_diff'] ?> дн.</span></div>
								</td>
								<td class="left">
									<div>
										<? echo $order['date_closed'] ?>	
										<? if ($order['is_closed']) { ?>
											<i class="fa fa-lock" aria-hidden="true" style="display:inline-block; color: #4ea24e;"></i>
											<? } else { ?>
											<i class="fa fa-unlock" aria-hidden="true" style="display:inline-block; color: #cf4a61;"></i>
										<? } ?>
									</div>
									<div><span style="font-size:10px; font-weight:700"><? echo $order['date_closed_diff'] ?> дн.</span></div>
								</td>
								<td class="left">
									<? if ($order['problems']) { ?>
										<? foreach ($order['problems'] as $problem) { ?>
											<span style="color:#cf4a61;"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> <? echo $problem; ?></span><br />
										<? } ?>
									<? } ?>											
								</td>
								<td class="left">
									<? if ($order['ppaid']) { ?>
										<span style="color:#00ad07;"><i class="fa fa-info-circle" aria-hidden="true"></i> <? echo $order['ppaid']; ?></span><br />
									<? } ?>		
									<? if ($order['punpaid']) { ?>
										<span style="color:#238ae6;"><i class="fa fa-info-circle" aria-hidden="true"></i> <? echo $order['punpaid']; ?></span><br />
									<? } ?>	
								</td>
								<td class="left">
									<? echo $order['total_text'] ?>
								</td>
								<td class="left">
									<div><? echo $order['already_paid_text'] ?></div>
									<? if ($order['is_time_bad']) { ?>
										<div><span class="_red"><i class="fa fa-clock-o" aria-hidden="true"></i> штраф</span></div>
									<? } ?>
								</td>
								<td class="left">
									<? echo $order['left_to_pay_text'] ?>
								</td>
							</tr>
						<? } ?>
						<tr class="lightblue">
							<td class="right" colspan="7">
								<b>Всего по <? echo $store['country_name']?></b>
							</td>						
							<td class="left">
								<b>Всего</b>
							</td>
							<td class="left">
								<b>Уже выплачено</b>
							</td>							
							<td class="left">
								<b>К выплате</b>
							</td>
						</tr>
						<tr>
							<td class="right" colspan="7">								
							</td>
							<td class="center">
								<div style="display:inline-block; padding:3px 5px; background:#FF8080; color:#fff; font-size:14px; font-weight:700;"><b><? echo $cstats['total_good_sum'][$store['country_name']]['total_text']; ?></b></div>
							</td>
							<td class="center">
								<div style="display:inline-block; padding:3px 5px; background:#FF8080; color:#fff; font-size:14px; font-weight:700;"><b><? echo $cstats['total_good_sum'][$store['country_name']]['already_paid_text']; ?></b></div>
							</td>
							<td class="center">
								<div style="display:inline-block; padding:3px 5px; background:#FF8080; color:#fff; font-size:14px; font-weight:700;"><b><? echo $cstats['total_good_sum'][$store['country_name']]['left_to_pay_text']; ?></b></div>
							</td>
						</tr>
						<tr>
							<td class="right" colspan="10" style="padding:5px;">								
							</td>
						</tr>
					<? } ?>
					
				</table>
			</div>
		</div>
	</div>
</div>
<div id="result_xls" style="display:none;"></div>
<script>
	$('body').on('keyup', 'input.currency_uah_value',function(){
		var _el = $(this);
		_el.next('i.fake_clicker').show();
	});
	$('body').on('change', 'input.currency_uah_value', function(){
		var _el = $(this);
		var _cur = $(this).attr('data-currency-id');
		var _val = $(this).val();
		var _elspan = $(this).next('span');
		var _ellabel = $(this).next('label');			
		
		var _recalculate_url = 'index.php?route=kp/salary/recalculateManagerAjax&token=<? echo $token; ?>';
		
		var filter_month = $('select[name=\'filter_month\']').attr('value');
		if (filter_month) {
			_recalculate_url += '&filter_month=' + encodeURIComponent(filter_month);
		}
		
		var filter_year = $('select[name=\'filter_year\']').attr('value');
		if (filter_year) {
			_recalculate_url += '&filter_year=' + encodeURIComponent(filter_year);
		}
		
		var filter_manager = $('select[name=\'filter_manager\']').attr('value');
		if (filter_manager) {
			_recalculate_url += '&filter_manager=' + encodeURIComponent(filter_manager);
		}
		
		var filter_dzioev_bug = $('input[name=\'filter_dzioev_bug\']:checked').val();
		if (filter_dzioev_bug !== undefined) {
			_recalculate_url += '&filter_dzioev_bug=1';
		}
		
		var filter_count_time = $('input[name=\'filter_count_time\']:checked').val();
		if (filter_count_time !== undefined) {
			_recalculate_url += '&filter_count_time=1';
		}
		
		var filter_count_time_days = $('input[name=\'filter_count_time_days\']').attr('value');
		if (filter_count_time_days) {
			_recalculate_url += '&filter_count_time_days=' + encodeURIComponent(filter_count_time_days);
		}
		
		$.ajax({
			url : 'index.php?route=kp/salary/updateCurrencyForRecounts&token=<? echo $token; ?>',
			type: 'POST',
			dataType : 'text',
			data : {
				currency : _cur,
				value: _val
			},
			beforeSend : function(){
				_el.removeClass('done').addClass('loading');
				$('i.fake_clicker').hide();
			},
			success : function(text){
				_el.removeClass('loading').addClass('done');			
			},
			complete : function(){
				$.ajax({
					url : _recalculate_url,
					type: 'GET',
					dataType : 'json',
					data:{},
					beforeSend : function(json){
						$('#das_total_in_uah').html('<i class="fa fa-spinner fa-spin" aria-hidden="true"></i>');
						$('#full_salary_txt').html('<i class="fa fa-spinner fa-spin" aria-hidden="true"></i>');
					},
					success : function(json){
						var keys = Object.keys(json);
						console.log(keys);
						keys.forEach(function(item){
							console.log(item + '=' + json[item]);
							if ($('#'+item).length > 0){
								$('#'+item).text(json[item]);
							}
						});
					},
					error : function(){
						console.log(json);
					}					
				});										
			},
			error : function(error){
				_el.removeClass('loading').addClass('error');
				console.log(error);
			}			
		});		
	});
</script>
<? if ($has_rights) { ?>
	<script>
		function setAllOrdersPaid(){
			
			$('input.salary_checkbox').each(function(){
				var _el = $(this);
				if (!(_el.is(':checked'))) {
					_el.trigger('change');
				}
			})
			
		}
		
		
		$('#close_month').change(function(){
			if ($(this).is(':checked')){
				$('#button_do_close_month').removeClass('redbg');
				$('#button_do_close_month').removeAttr('disabled');	
				$('#button_do_close_month').on('click', function(){
					setAllOrdersPaid();
				});
				} else {
				$('#button_do_close_month').addClass('redbg');
				$('#button_do_close_month').attr('disabled', 'disabled');
				$('#button_do_close_month').prop('onclick', null).off('click');				
			}						
		});
		
		$('input.salary_checkbox').change(function(){
			var _el = $(this);
			var _oid = $(this).attr('data-order-id');
			$.ajax({
				url : 'index.php?route=sale/order/setOrderSalaryPaidAjax&order_id=' + _oid + '&token=<?php echo $token; ?>',
				type : 'GET',
				success : function(i){
					if (i == 1){
						_el.attr('checked', 'checked');						
						} else {
						_el.removeAttr('checked', 'checked');						
					}					
				},
				error : function(e){
					console.log(e);
				}
			});
			
		});
	</script>
	<? } else { ?>
	<style>
		.salary_checkbox{cursor: not-allowed !important;}
		.salary_checkbox:before, .salary_checkbox:after{cursor: not-allowed !important;}
	</style>
<? } ?>
<script>
	$(document).ready(function(){
		$("#avg_csi_by_orders").rateYo({
			rating: <? echo (float)$cstats['avg_csi_by_orders']; ?>,
			precision: 1,
			starWidth: "24px",
			readOnly: true,
			normalFill: "#ccc",
			multiColor: {																
				"startColor": "#cf4a61", //RED
				"endColor"  : "#4ea24e"  //GREEN
			}
		});	
		
		$("#avg_csi_by_manager").rateYo({
			rating: <? echo (float)$cstats['avg_csi_by_manager']; ?>,
			precision: 1,
			starWidth: "24px",
			readOnly: true,
			normalFill: "#ccc",
			multiColor: {																
				"startColor": "#cf4a61", //RED
				"endColor"  : "#4ea24e"  //GREEN
			}
		});	
	});
</script>
<script type="text/javascript"><!--	
	function filterpdf(details) {
		url = 'index.php?route=kp/salary/countManagers&do_pdf=1&token=<?php echo $token; ?>';
		
		var filter_month = $('select[name=\'filter_month\']').attr('value');
		
		if (filter_month) {
			url += '&filter_month=' + encodeURIComponent(filter_month);
		}
		
		var filter_year = $('select[name=\'filter_year\']').attr('value');
		
		if (filter_year) {
			url += '&filter_year=' + encodeURIComponent(filter_year);
		}
		
		var filter_manager = $('select[name=\'filter_manager\']').attr('value');
		
		if (filter_manager) {
			url += '&filter_manager=' + encodeURIComponent(filter_manager);
		}
		
		var filter_dzioev_bug = $('input[name=\'filter_dzioev_bug\']:checked').val();
		
		if (filter_dzioev_bug !== undefined) {
			url += '&filter_dzioev_bug=1';
		}
		
		var filter_count_time = $('input[name=\'filter_count_time\']:checked').val();
		
		if (filter_count_time !== undefined) {
			url += '&filter_count_time=1';
		}
		
		var filter_count_time_days = $('input[name=\'filter_count_time_days\']').attr('value');
		
		if (filter_count_time_days) {
			url += '&filter_count_time_days=' + encodeURIComponent(filter_count_time_days);
		}
		
		if (!details){
			url += '&do_not_print_details=1';
		}
		
		location = url;
	}
//--></script>
<script type="text/javascript"><!--	
	function filterxls() {
		url = 'index.php?route=kp/salary/consolidateCountManagers&token=<?php echo $token; ?>';
		
		var filter_month = $('select[name=\'filter_month\']').attr('value');
		
		if (filter_month) {
			url += '&filter_month=' + encodeURIComponent(filter_month);
		}
		
		var filter_year = $('select[name=\'filter_year\']').attr('value');
		
		if (filter_year) {
			url += '&filter_year=' + encodeURIComponent(filter_year);
		}
		
		var filter_manager = $('select[name=\'filter_manager\']').attr('value');
		
		if (filter_manager) {
			url += '&filter_manager=' + encodeURIComponent(filter_manager);
		}
		
		var filter_dzioev_bug = $('input[name=\'filter_dzioev_bug\']:checked').val();
		
		if (filter_dzioev_bug !== undefined) {
			url += '&filter_dzioev_bug=1';
		}
		
		var filter_count_time = $('input[name=\'filter_count_time\']:checked').val();
		
		if (filter_count_time !== undefined) {
			url += '&filter_count_time=1';
		}
		
		var filter_count_time_days = $('input[name=\'filter_count_time_days\']').attr('value');
		
		if (filter_count_time_days) {
			url += '&filter_count_time_days=' + encodeURIComponent(filter_count_time_days);
		}
		
		location = url;
	}
//--></script>
<script type="text/javascript"><!--	
	function filterxlssimple() {
		url = 'index.php?route=kp/salary/consolidateCountManagers&simple=1&token=<?php echo $token; ?>';
		
		var filter_month = $('select[name=\'filter_month\']').attr('value');
		
		if (filter_month) {
			url += '&filter_month=' + encodeURIComponent(filter_month);
		}
		
		var filter_year = $('select[name=\'filter_year\']').attr('value');
		
		if (filter_year) {
			url += '&filter_year=' + encodeURIComponent(filter_year);
		}
		
		var filter_manager = $('select[name=\'filter_manager\']').attr('value');
		
		if (filter_manager) {
			url += '&filter_manager=' + encodeURIComponent(filter_manager);
		}
		
		var filter_dzioev_bug = $('input[name=\'filter_dzioev_bug\']:checked').val();
		
		if (filter_dzioev_bug !== undefined) {
			url += '&filter_dzioev_bug=1';
		}
		
		var filter_count_time = $('input[name=\'filter_count_time\']:checked').val();
		
		if (filter_count_time !== undefined) {
			url += '&filter_count_time=1';
		}
		
		var filter_count_time_days = $('input[name=\'filter_count_time_days\']').attr('value');
		
		if (filter_count_time_days) {
			url += '&filter_count_time_days=' + encodeURIComponent(filter_count_time_days);
		}
		
		location = url;
	}
//--></script>
<script type="text/javascript"><!--
	
	function filter() {
		url = 'index.php?route=kp/salary/countManagers&token=<?php echo $token; ?>';
		
		var filter_month = $('select[name=\'filter_month\']').attr('value');
		
		if (filter_month) {
			url += '&filter_month=' + encodeURIComponent(filter_month);
		}
		
		var filter_year = $('select[name=\'filter_year\']').attr('value');
		
		if (filter_year) {
			url += '&filter_year=' + encodeURIComponent(filter_year);
		}
		
		var filter_manager = $('select[name=\'filter_manager\']').attr('value');
		
		if (filter_manager) {
			url += '&filter_manager=' + encodeURIComponent(filter_manager);
		}
		
		var filter_dzioev_bug = $('input[name=\'filter_dzioev_bug\']:checked').val();
		
		if (filter_dzioev_bug !== undefined) {
			url += '&filter_dzioev_bug=1';
		}
		
		var filter_count_time = $('input[name=\'filter_count_time\']:checked').val();
		
		if (filter_count_time !== undefined) {
			url += '&filter_count_time=1';
		}
		
		/*	
			var filter_count_time_days = $('input[name=\'filter_count_time_days\']').attr('value');
			
			if (filter_count_time_days) {
			url += '&filter_count_time_days=' + encodeURIComponent(filter_count_time_days);
			}
		*/	
		
		location = url;
	}
//--></script>
<script type="text/javascript"><!--
	$(document).ready(function() {
		$('#date').datepicker({dateFormat: 'yy-mm-dd'});
		$('#date_bf').datepicker({dateFormat: 'mm-dd'});
		$('#date_bt').datepicker({dateFormat: 'mm-dd'});
		
		$(".rateYo").rateYo({
			precision: 1,
			starWidth: "18px",
			readOnly: true,
			normalFill: "#ccc",
			multiColor: {																
				"startColor": "#cf4a61", //RED
				"endColor"  : "#4ea24e"  //GREEN
			}
		});
	});
//--></script>
<?php echo $footer; ?>	
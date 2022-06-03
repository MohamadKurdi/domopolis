<?php echo $header; ?>
<style>
	table.table16 tr td {font-size:16px;padding-top:10px !important; padding-bottom:10px !important;}
	tr.hovered td {background-color:#faf9f1 !important;}
	tr.blue:hover td {background:#99CCFF !important; color:#FFF !important;}
	tr.lightblue td {background:#CCFFFF !important;}
	tr.lightgreen td {background:#CCFFCC !important;}
	td.td_alert {background:#FF99CC !important;}
	span._red{display:inline-block; padding:3px 5px; background:#FF8080; color:#fff;}
	span._orange{display:inline-block; padding:3px 5px; background:#FFCC99; color:#fff;}
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
			<h1>Ведомость учета заказов по клиент-сервису</h1>				  					
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
									<? for ($i=date('Y', strtotime("-1 year")); $i<=date('Y'); $i++) { ?>										
										<? if ($filter_year == $i) { ?>
											<option value="<? echo $i; ?>" selected="selected"><? echo $i; ?></option>
											<? } else { ?>
											<option value="<? echo $i; ?>"><? echo $i; ?></option>
										<? } ?>
									<? } ?>
								</select>
							</td>
							
							<td>
								<p><i class="fa fa-user"></i> Клиент - менеджер</p>
								<select name="filter_manager">
									<option value="*">-- Выберите менеджера --</option>
									<?php foreach ($managers as $manager) { ?>
										<?php if ($manager['user_id'] == $filter_manager) { ?>
											<option value="<?php echo $manager['user_id'] ?>" selected="selected"><?php echo $manager['realname']; ?></option>
											<?php } else { ?>
											<option value="<?php echo $manager['user_id'] ?>"><?php echo $manager['realname']; ?></option>
										<?php } ?>
									<?php } ?>
								</select>
								
							</td>
							
							<td colspan="" align="right">
								<p>	&#160;</p>
							<a onclick="filter();" class="button">Сформировать</a></td>
							<td colspan="" align="right">	
								<p>	&#160;</p>
								<a onclick="filterpdf();" class="button"><i class="fa fa-file-pdf-o"></i> PDF</a>
							</td>	
						</tr>
					</tbody>
				</table>
			</div>
			<div style="margin-top:10px;"> 
				<table class="list table16">		
					
					<tr class="hovered">
						<td colspan="4" class="left"><b>Сводная таблица</b></td>																								
					</tr>
					
					<tr>
						<td class="left">
							<i class="fa fa-clock-o"  aria-hidden="true"></i>&nbsp;Всего дней
						</td>
						<td class="center">
							<b><? echo $cstats['total_days']; ?></b>
						</td>
						<td class="left">
							<i class="fa fa-refresh"  aria-hidden="true"></i>&nbsp;Всего кликов
						</td>
						<td class="center">
							<b><? echo $cstats['total_actions']; ?></b>
						</td>
					</tr>
					
					<tr>
						<td class="left">
							<i class="fa fa-clock-o"  aria-hidden="true"></i>&nbsp;Всего часов
						</td>
						<td class="center">
							<b><? echo $cstats['total_time']; ?></b>
						</td>
						<td class="left">
							<i class="fa fa-envelope"  aria-hidden="true"></i>&nbsp;Всего писем
						</td>
						<td class="center">
							<b><? echo $cstats['total_emails']; ?></b>
						</td>
					</tr>
					
					<tr>
						<td class="left">
							<i class="fa fa-phone" aria-hidden="true"></i>&nbsp;Всего входящих
						</td>
						<td class="center">
							<b><? echo $cstats['total_inbound']; ?><br />
								<? echo $cstats['total_inbound_duration']; ?>
							</b>
						</td>
						<td class="left">
							<i class="fa fa-phone" aria-hidden="true"></i>&nbsp;Всего исходящих
						</td>
						<td class="center">
							<b><? echo $cstats['total_outbound']; ?><br />
								<? echo $cstats['total_outbound_duration']; ?>
							</b>
						</td>
					</tr>
					
					<tr>
						<td class="left">
							<i class="fa fa-birthday-cake" aria-hidden="true"></i>&nbsp;Всего Дней Рождения
						</td>
						<td class="center">
							<b><? echo $cstats['total_bdays']; ?></b>
						</td>
						<td class="left">
							<i class="fa fa-handshake-o"></i>&nbsp;Всего CSI
						</td>
						<td class="center">
							<b><? echo $cstats['total_csi']; ?></b>
						</td>
					</tr>
					
					<tr>
						<td class="left">
							<i class="fa fa-cart-arrow-down"></i>&nbsp;Всего оформлено заказов
						</td>
						<td class="center">
							<b><? echo $cstats['total_bad_orders']; ?></b>
						</td>
						<td class="left">
							<i class="fa fa-cart-arrow-down"></i>&nbsp;Всего выполнено заказов
						</td>
						<td class="center">
							<b><? echo $cstats['total_good_orders']; ?></b>
						</td>
					</tr>
					
					<? foreach ($cstats['total_good_sum'] as $country => $sum) { ?>
						<tr>
							<td class="left">							
							</td>
							<td class="center">							
							</td>
							<td class="right">
								<b><? echo $country; ?></b>
							</td>
							<td class="center">
								<div style="display:inline-block; padding:3px 5px; background:#FF8080; color:#fff; font-size:14px; font-weight:700;"><b><? echo $sum['text']; ?></b></div>
							</td>
						</tr>
					<? } ?>
					
					<? if ($has_rights) { ?>
						
						<tr>							
							<td colspan="2">							
							</td>
							<td colspan="2" class="right">
								<input type="checkbox" class="checkbox" id="close_month" />
								<label for="close_month"></label>
								
								<a class="button redbg" id="button_do_close_month" onclick="" style="">Фиксировать оплату всех заказов</a>
							</td>						
						</tr>
						
					<? } ?>
					
				</table>
			</div>
			<div style="margin-top:10px;"> 
				<table class="list">		
					
					<tr class="hovered">
						<td colspan="5" class="left"><b>Результаты по промокодам</b></td>																								
					</tr>
					
					<? foreach ($promocodes as $promocode) { ?>
						<tr class="hovered">
							<td class="left" colspan="6">
								<b><? echo $promocode['code']; ?></b>
							</td>
						</tr>
						<? if ($promocode['actiontemplate']) { ?>	
							<tr class="lightblue">
								<td class="right">
									<b>Эффективность шаблона</b>
								</td>
								<td class="right">
									Шаблон
								</td>
								<td class="center">
									Отправок
								</td>
								<td class="center">
								Открытий
								</td>
								<td class="center">
								Кликов
								</td>
							</tr>
							<tr>
								<td></td>
								<td class="right">								
									<div class="status_color" style="display:inline-block; padding:3px 5px; background:#1f4962; color:#fff; font-size:14px;"><? echo $promocode['actiontemplate']; ?></div>
								</td>
								<td class="center">
									<? echo $promocode['actiontemplate_count']; ?> 
								</td>
								<td class="center">
									<? echo $promocode['actiontemplate_opened']; ?> 
									<span class="<? echo $promocode['cao_class'] ?>">(<? echo $promocode['conv_actiontemplate_opened'] ?>%)</span>
								</td>
								<td class="center">
									<? echo $promocode['actiontemplate_clicked']; ?>
									<span class="<? echo $promocode['cac_class'] ?>">(<? echo $promocode['conv_actiontemplate_clicked'] ?>%)</span>
								</td>
							</tr>
						<? } ?>
						
						<tr class="lightblue">
							<td class="right">
								<b>Эффективность продаж</b>
							</td>
							<td class="center">
								Оформлено за все время (c <? echo $promocode['actiontemplate_date_from'] ?>)
							</td>
							<td class="center">
								Выполнено за все время (c <? echo $promocode['actiontemplate_date_from'] ?>)
							</td>
							<td class="center">
								Оформлено за месяц
							</td>
							<td class="center">
								Выполнено за месяц
							</td>								
						</tr>
						<tr>
							<td></td>
							<td class="center">										
								<? if ($promocode['ub_class']) { ?>
									<b><? echo $promocode['usage_bad']; ?></b>
									<span class="<? echo $promocode['ub_class'] ?>">(<? echo $promocode['conv_usage_bad'] ?>%)</span>
									<? } else { ?>
									<b><? echo $promocode['usage_bad']; ?></b>
								<? } ?>
							</td>
							<td class="center">
								<? if ($promocode['ug_class']) { ?>
									<b><? echo $promocode['usage_good']; ?></b>
									<span class="<? echo $promocode['ug_class'] ?>">(<? echo $promocode['conv_usage_good'] ?>%)</span>
									<? } else { ?>
									<b><? echo $promocode['usage_good']; ?></b>
								<? } ?>
							</td>
							<td class="center">
								<? if ($promocode['ubm_class']) { ?>
									<b><? echo $promocode['usage_bad_month']; ?></b>
									<span class="<? echo $promocode['ubm_class'] ?>">(<? echo $promocode['conv_usage_bad_month'] ?>%)</span>
									<? } else { ?>
									<b><? echo $promocode['usage_bad_month']; ?></b>
								<? } ?>
							</td>
							<td class="center" width="250px" style="width:250px;">
								<b><? echo $promocode['usage_good_month']; ?></b>
							</td>
						</tr>
						<? if ($promocode['orders']) { ?>
							<? foreach ($promocode['orders'] as $country_array) { ?>
								<tr>
									<td colspan="4"></td>
									<td colspan="1">
										<table>
											<tr class="lightgreen">
												<td class="left" nowrap style="white-space: nowrap" colspan="3">
													<b><? echo $country_array['country_name'] ?></b>
												</td>												
											</tr>
											<tr>
												<td class="left">
													Оплачен
												</td>
												<td class="left">
													Код
												</td>
												<td class="left">
													Итог
												</td>
											</tr>
											<? foreach ($country_array['orders'] as $order) { ?>
												<tr>
													<td class="left">
														<input data-order-id="<? echo $order['order_id'] ?>" type="checkbox" class="checkbox salary_checkbox" <? if (!$has_rights) { ?>disabled="disabled"<? } ?> id="salary_<? echo $order['order_id'] ?>" <? if ($order['salary_paid']) { ?>checked="checked"<? } ?> />
														<label class="salary_checkbox" for="salary_<? echo $order['order_id'] ?>"></label>
													</td>
													<td class="left">
														<? echo $order['order_id'] ?>&nbsp;<a href="<? echo $order['url'] ?>" target="_blank"><i class="fa fa-external-link" aria-hidden="true"></i></a>
													</td>
													<td class="left">
														<? echo $order['total_text'] ?>
													</td>
												</tr>
											<? } ?>
											<tr>
												<td class="right" colspan="2">
													<b>Всего</b>
												</td>
												<td nowrap style="white-space: nowrap">	
													<div style="display:inline-block; padding:3px 5px; background:#FF8080; color:#fff; font-size:14px; font-weight:700;"><? echo $country_array['total_text']; ?></div>
												</td>
											</tr>
										</table>
									</td>								
								</tr>																
							<? } ?>
						<? } ?>
						<tr>
							<td class="left" colspan="6">								
							</td>
						</tr>
					<? } ?>
					
				</table>
			</div>
		</div>
	</div>
</div>

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
				$('#button_do_close_month').prop('onclick',null).off('click');				
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
<script type="text/javascript"><!--	
	function filterpdf() {
		url = 'index.php?route=kp/salary/countCustomerService&do_pdf=1&token=<?php echo $token; ?>';
		
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
		
		location = url;
	}
//--></script>
<script type="text/javascript"><!--
	
	function filter() {
		url = 'index.php?route=kp/salary/countCustomerService&token=<?php echo $token; ?>';
		
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
<?php echo $header; ?>
<style>
.tooltip{position:absolute;z-index:1070;display:block;font-family:"Helvetica Neue",Helvetica,Arial,sans-serif;font-style:normal;font-weight:400;letter-spacing:normal;line-break:auto;line-height:1.42857;text-align:left;text-align:start;text-decoration:none;text-shadow:none;text-transform:none;white-space:normal;word-break:normal;word-spacing:normal;word-wrap:normal;font-size:11px;opacity:0;filter:alpha(opacity=0)}.tooltip.in{opacity:.9;filter:alpha(opacity=90)}.tooltip.top{margin-top:-3px;padding:5px 0}.tooltip.right{margin-left:3px;padding:0 5px}.tooltip.bottom{margin-top:3px;padding:5px 0}.tooltip.left{margin-left:-3px;padding:0 5px}.tooltip-inner{max-width:200px;padding:3px 8px;color:#fff;text-align:center;background-color:#000;border-radius:3px}.tooltip-arrow{position:absolute;width:0;height:0;border-color:transparent;border-style:solid}.tooltip.top .tooltip-arrow{bottom:0;left:50%;margin-left:-5px;border-width:5px 5px 0;border-top-color:#000}.tooltip.top-left .tooltip-arrow{bottom:0;right:5px;margin-bottom:-5px;border-width:5px 5px 0;border-top-color:#000}.tooltip.top-right .tooltip-arrow{bottom:0;left:5px;margin-bottom:-5px;border-width:5px 5px 0;border-top-color:#000}.tooltip.right .tooltip-arrow{top:50%;left:0;margin-top:-5px;border-width:5px 5px 5px 0;border-right-color:#000}.tooltip.left .tooltip-arrow{top:50%;right:0;margin-top:-5px;border-width:5px 0 5px 5px;border-left-color:#000}.tooltip.bottom .tooltip-arrow{top:0;left:50%;margin-left:-5px;border-width:0 5px 5px;border-bottom-color:#000}.tooltip.bottom-left .tooltip-arrow{top:0;right:5px;margin-top:-5px;border-width:0 5px 5px;border-bottom-color:#000}.tooltip.bottom-right .tooltip-arrow{top:0;left:5px;margin-top:-5px;border-width:0 5px 5px;border-bottom-color:#000}
</style>
<div id="content">
	<div class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
			<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
		<?php } ?>
	</div>
	<?php if ($error_warning) { ?>
		<div class="warning"><?php echo $error_warning; ?></div>
	<?php } ?>
	<div class="box">
		<div class="heading order_head">
			<h1><img src="view/image/customer.png" alt="" /> <?php echo $heading_title; ?></h1>
			<div class="buttons">
			<a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
		</div>
		<div class="content">
			<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
				<table class="form">
					<tr>					
						<td>Название сегмента: <input style="width:350px" type="text" name="name" value="<? echo $name; ?>" />&nbsp;
							<? if (isset($segment_id)) { ?>
								<a class="button" id="button_preload"><i class='fa fa-eye' aria-hidden='true'></i></a>&nbsp;										
								<?php if ($enabled) { ?>
									<a href="<?php echo $view_link; ?>" class="button" target="_blank">
									<i class='fa fa-list' aria-hidden='true'></i></a>&nbsp;
									<a class="button" onclick="$('html, body').animate({scrollTop: $('#dynamics').offset().top}, 2000);"><i class='fa fa-area-chart' aria-hidden='true'></i></a>&nbsp;									
								<? } ?>
								<a class="button" id="button_preload_sql">SQL</a>&nbsp;
								<? } ?>&nbsp;&nbsp;&nbsp;<select name="enabled">
								<?php if ($enabled) { ?>
									<option value="1" selected="selected">Включен</option>
									<option value="0">Выключен</option>
									<?php } else { ?>
									<option value="1">Включен</option>
									<option value="0" selected="selected">Выключен</option>
								<?php } ?>
							</select>
							&nbsp;&nbsp;&nbsp;TTL нового покупателя, дней: <input style="width:40px" type="text" name="new_days" value="<? echo $new_days; ?>" />
							&nbsp;&nbsp;&nbsp;Группа: <input style="width:100px" type="text" name="group" value="<? echo $group; ?>" />
						</td>
					</tr>		
					<tr>
						<td>
							<div id="preload"></div>
						</td>
					</tr>
					<tr>	
						
						<td>
							<table class="list">
								<thead>
									<tr>
										<th colspan="7" class="th_style"></th>
									</tr>
									<tr>
										<td class="left bordSTYLE" colspan="">Заказы и финансы</td>
									</tr>
									<tr style="height: 5px;"></tr>
								</thead>
								
								<tr>
									<td class="left">
										Всего заказов
									</td>
									<td class="left">
										Выполнено заказов
									</td>
									<td class="left">
										Отменено заказов
									</td>
									<td class="left">
										Средний CSI
									</td>
									<td class="left">
										Потраченная сумма
									</td>
									<td class="left">
										Средний чек
									</td>									
								</tr>
								<tr>
									<td class="left">
										<input type="text" name="order_count" value="<? echo $order_count; ?>" />
									</td>
									<td class="left">
										<input type="text" name="order_good_count" value="<? echo $order_good_count; ?>" />
									</td>
									<td class="left">
										<input type="text" name="order_bad_count" value="<? echo $order_bad_count; ?>" />
									</td>
									<td class="left">
										<input type="text" name="avg_csi" value="<? echo $avg_csi; ?>" />
									</td>
									<td class="left">
										<input type="text" name="total_cheque" value="<? echo $total_cheque; ?>" />
										<div style="margin-top:5px"><input class="checkbox" type="checkbox" id="total_cheque_only_selected" name="total_cheque_only_selected" value="1" <? if (isset($total_cheque_only_selected) && $total_cheque_only_selected) { ?>checked="checked"<? } ?> />
										<label for="total_cheque_only_selected">Только по выбр</label> <i class="fa fa-info-circle ktooltip_hover" title="Параметр применяется только к выбранным брендам и / или категориям покупки"></i>
									</td>
									<td class="left">
										<input type="text" name="avg_cheque" value="<? echo $avg_cheque; ?>" />
									</td>									
								</tr>
							</table>
							<table class="list">
								<thead>
									<tr>
										<th colspan="5" class="th_style"></th>
									</tr>
									<tr>
										<td class="left bordSTYLE" colspan="">Временные рамки</td>
									</tr>
									<tr style="height: 5px;"></tr>
								</thead>
								<tr>
									<td class="left">
										Дата первого заказа
									</td>
									<td class="left">
										Дата последнего заказа
									</td>
									<td class="left">
										Дата первого выполн. заказа
									</td>
									<td class="left">
										Дата последнего выполн. заказа
									</td>
									<td class="left">
										Дата добавления
									</td>
								</tr>
								<tr>
									<td class="left">
										<input type="text" class="date-new" style="width:90px;" name="order_first_date_from" value="<? echo $order_first_date_from; ?>" /> -
										<input type="text" class="date-new" style="width:90px;" name="order_first_date_to" value="<? echo $order_first_date_to; ?>" />
									</td>
									<td class="left">
										<input type="text" class="date-new" style="width:90px;" name="order_last_date_from" value="<? echo $order_last_date_from; ?>" /> - 
										<input type="text" class="date-new" style="width:90px;" name="order_last_date_to" value="<? echo $order_last_date_to; ?>" />
									</td>
									<td class="left">
										<input type="text" class="date-new" style="width:90px;" name="order_good_first_date_from" value="<? echo $order_good_first_date_from; ?>" /> - 
										<input type="text" class="date-new" style="width:90px;" name="order_good_first_date_to" value="<? echo $order_good_first_date_to; ?>" />
									</td>
									<td class="left">
										<input type="text" class="date-new" style="width:90px;" name="order_good_last_date_from" value="<? echo $order_good_last_date_from; ?>" /> - 
										<input type="text" class="date-new" style="width:90px;" name="order_good_last_date_to" value="<? echo $order_good_last_date_to; ?>" />
									</td>
									<td class="left">
										<input type="text" class="date-new" style="width:90px;" id="date_added_from" name="date_added_from" value="<? echo $date_added_from; ?>" /> - 
										<input type="text" class="date-new" style="width:90px;margin-bottom: 5px" id="date_added_to" name="date_added_to" value="<? echo $date_added_to; ?>" /><br />
										<input class="checkbox" type="checkbox" id="added_year" name="added_year" value="1" <? if (isset($added_year) && $added_year) { ?>checked="checked"<? } ?> />
										<label for="added_year">Без года</label>
									</td>
								</tr>
								</table>
							<table class="list">
								<thead>
									<tr>
										<th colspan="5" class="th_style"></th>
									</tr>
									<tr>
										<td class="left bordSTYLE" colspan="">Информация</td>
									</tr>
									<tr style="height: 5px;"></tr>
								</thead>
								<tr>
									<td class="left">
										Страна
									</td>
									<td class="left">
										Город
									</td>
									<td class="left">
										Пол
									</td>
									<td class="left">
										Группа покупателей
									</td>
									<td class="left">
										День рождения
									</td>
								</tr>
								<tr>
									<td class="left">
										<div class="scrollbox">
											<?php $class = 'odd'; foreach ($countries as $single_country) { ?>
												<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
												<div class="<?php echo $class; ?>">
													<?php if (in_array($single_country['country_id'], $country_id)) { ?>
														<input id="<?php echo $single_country['country_id']; ?>" class="checkbox" type="checkbox" name="country_id[]" value="<?php echo $single_country['country_id']; ?>" checked="checked" />
														<label for="<?php echo $single_country['country_id']; ?>"><?php echo $single_country['name']; ?></label>
														<?php } else { ?>
														<input id="<?php echo $single_country['country_id']; ?>" class="checkbox" type="checkbox" name="country_id[]" value="<?php echo $single_country['country_id']; ?>" />
														<label for="<?php echo $single_country['country_id']; ?>"><?php echo $single_country['name']; ?></label>
													<?php } ?>
												</div>
											<?php } ?>
										</div>
										<a class="select_all" onclick="$(this).parent().find(':checkbox').attr('checked', true);">Выделить всё</a><a class="remove_selection" onclick="$(this).parent().find(':checkbox').attr('checked', false);">Снять выделение</a>
									</td>
									<td class="left">
										<textarea name="city"><? echo $city; ?></textarea>
										<span class="help">каждый в новой строке, для исключения: !Москва</span>
									</td>
									<td class="left">
										<select name="gender">
											<option value="0" <? if ($gender == 0) { ?>selected="selected"<? } ?>>- Не выбрано - </option>
											<option value="1" <? if ($gender == 1) { ?>selected="selected"<? } ?>>Мужчины</option>
											<option value="2" <? if ($gender == 2) { ?>selected="selected"<? } ?>>Женщины</option>											
										</select>
									</td>
									<td class="left">
										<div class="scrollbox">
											<?php $class = 'odd'; foreach ($customer_groups as $single_customer_group) { ?>
												<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
												<div class="<?php echo $class; ?>">
													<?php if (in_array($single_customer_group['customer_group_id'], $customer_group_id)) { ?>
														<input class="checkbox" id="<?php echo $single_customer_group['customer_group_id']; ?>" type="checkbox" name="customer_group_id[]" value="<?php echo $single_customer_group['customer_group_id']; ?>" checked="checked" />
														<label for="<?php echo $single_customer_group['customer_group_id']; ?>"><?php echo $single_customer_group['name']; ?></label>
														<?php } else { ?>
														<input class="checkbox" id="<?php echo $single_customer_group['customer_group_id']; ?>" type="checkbox" name="customer_group_id[]" value="<?php echo $single_customer_group['customer_group_id']; ?>" />
														<label for="<?php echo $single_customer_group['customer_group_id']; ?>"><?php echo $single_customer_group['name']; ?></label>
													<?php } ?>
												</div>
											<?php } ?>
										</div>
										<a class="select_all" onclick="$(this).parent().find(':checkbox').attr('checked', true);">Выделить всё</a><a class="remove_selection" onclick="$(this).parent().find(':checkbox').attr('checked', false);">Снять выделение</a>
									</td>
									<td class="left">
										<input type="text" class="date-new" style="width:90px;" id="birthday_from" name="birthday_from" value="<? echo $birthday_from; ?>" /> - 
										<input type="text" class="date-new" style="width:90px;margin-bottom: 5px" id="birthday_to" name="birthday_to" value="<? echo $birthday_to; ?>" /><br />
										<input class="checkbox" type="checkbox" id="birthday_year" name="birthday_year" value="1" <? if (isset($birthday_year) && $birthday_year) { ?>checked="checked"<? } ?> />
										<label for="birthday_year">Без года</label>
									</td>
								</tr>
							</table>
							<table class="list">
								<thead>
									<tr>
										<th colspan="6" class="th_style"></th>
									</tr>
									<tr>
										<td class="left bordSTYLE" colspan="">Вовлеченность</td>
									</tr>
									<tr style="height: 5px;"></tr>
								</thead>
								<tr>									
									<td class="left">	
										Открытия писем
									</td>
									<td class="left">	
										Push подписка
									</td>
									<td class="left">
										Количество звонков
									</td>
									<td class="left">
										Среднее время звонка
									</td>
									<td class="left">
										Использование купона
									</td>
									<td class="left">
										Источник ручного добавления
									</td>
								</tr>
								<tr>
									<td class="left">			
										<input type="text" name="mail_opened" value="<? echo $mail_opened; ?>" />
									</td>
									<td class="left">
										<input class="checkbox" type="checkbox" id="has_push" name="has_push" value="1" <? if (isset($has_push) && $has_push) { ?>checked="checked"<? } ?> />
										<label for="has_push"></label>
									</td>
									<td class="left">
										<input type="text" name="total_calls" value="<? echo $total_calls; ?>" />
									</td>
									<td class="left"> 
										<input type="text" name="avg_calls_duration" value="<? echo $avg_calls_duration; ?>" />
									</td>
									<td class="left"> 
										
										<div class="scrollbox">
											<?php $class = 'odd'; foreach ($coupons as $single_coupon) { ?>
												<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
												<div class="<?php echo $class; ?>">
													<?php if (in_array($single_coupon['code'], $coupon)) { ?>
														<input class="checkbox" id="<?php echo $single_coupon['code']; ?>" type="checkbox" name="coupon[]" value="<?php echo $single_coupon['code']; ?>" checked="checked" />
														<label for="<?php echo $single_coupon['code']; ?>"><?php echo $single_coupon['code']; ?></label>
														<?php } else { ?>
														<input class="checkbox" id="<?php echo $single_coupon['code']; ?>" type="checkbox" name="coupon[]" value="<?php echo $single_coupon['code']; ?>" />
														<label for="<?php echo $single_coupon['code']; ?>"><?php echo $single_coupon['code']; ?></label>
													<?php } ?>
												</div>
											<?php } ?>
										</div>
										<a class="select_all" onclick="$(this).parent().find(':checkbox').attr('checked', true);">Выделить всё</a><a class="remove_selection" onclick="$(this).parent().find(':checkbox').attr('checked', false);">Снять выделение</a>
										
										
									</td>
									<td class="left">
										<div class="scrollbox">
											<?php $class = 'odd'; foreach ($sources as $single_source) { ?>
												<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
												<div class="<?php echo $class; ?>">
													<?php if (in_array($single_source['source'], $source)) { ?>
														<input class="checkbox" id="<?php echo $single_source['source']; ?>" type="checkbox" name="source[]" value="<?php echo $single_source['source']; ?>" checked="checked" />
														<label for="<?php echo $single_source['source']; ?>"><?php echo $single_source['source']; ?></label>
														<?php } else { ?>
														<input class="checkbox" id="<?php echo $single_source['source']; ?>" type="checkbox" name="source[]" value="<?php echo $single_source['source']; ?>" />
														<label for="<?php echo $single_source['source']; ?>"><?php echo $single_source['source']; ?></label>
													<?php } ?>
												</div>
											<?php } ?>
										</div>
										<a class="select_all" onclick="$(this).parent().find(':checkbox').attr('checked', true);">Выделить всё</a><a class="remove_selection" onclick="$(this).parent().find(':checkbox').attr('checked', false);">Снять выделение</a>
									</td>
								</tr>
							</table>
							<table class="list">
								<thead>
									<tr>
										<th colspan="6" class="th_style"></th>
									</tr>
									<tr>
										<td class="left bordSTYLE">Интересы</td>
									</tr>
									<tr style="height: 5px;"></tr>
								</thead>
								<tr>
									<td class="left">	
										Бренды просмотра
									</td>
									<td class="left" colspan="2">
										Бренды покупки
									</td>
									<td class="left" >
										Категории просмотра
									</td>
									<td class="left" >
										Категории покупки
									</td>
									<td class="left">
										Источник первого заказа
									</td>
								</tr>
								<tr>
									<td class="left">
										<div class="scrollbox" style="height: 400px; overflow-x: auto; width: 100%;">
											<?php $class = 'odd'; foreach ($manufacturers as $single_manufacturer) { ?>
												<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
												<div class="<?php echo $class; ?>">
													<?php if (in_array($single_manufacturer['manufacturer_id'], $manufacturer_view)) { ?>
														<input class="checkbox" id="manufacturer_<?php echo $single_manufacturer['manufacturer_id']; ?>" type="checkbox" name="manufacturer_view[]" value="<?php echo $single_manufacturer['manufacturer_id']; ?>" checked="checked" />
														<label for="manufacturer_<?php echo $single_manufacturer['manufacturer_id']; ?>"><?php echo $single_manufacturer['name']; ?></label>
														<?php } else { ?>
														<input class="checkbox" id="manufacturer_<?php echo $single_manufacturer['manufacturer_id']; ?>" type="checkbox" name="manufacturer_view[]" value="<?php echo $single_manufacturer['manufacturer_id']; ?>" />
														<label for="manufacturer_<?php echo $single_manufacturer['manufacturer_id']; ?>"><?php echo $single_manufacturer['name']; ?></label>
													<?php } ?>
												</div>
											<?php } ?>
										</div>
										<a class="select_all" onclick="$(this).parent().find(':checkbox').attr('checked', true);">Выделить всё</a><a class="remove_selection" onclick="$(this).parent().find(':checkbox').attr('checked', false);">Снять выделение</a>
									</td>
									<td class="left" colspan="2">
										<div class="scrollbox" style="height: 400px; overflow-x: auto; width: 100%;">
											<?php $class = 'odd'; foreach ($manufacturers as $single_manufacturer) { ?>
												<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
												<div class="<?php echo $class; ?>">
													<?php if (in_array($single_manufacturer['manufacturer_id'], $manufacturer_bought)) { ?>
														<input class="checkbox" id="<?php echo $single_manufacturer['manufacturer_id']; ?>" type="checkbox" name="manufacturer_bought[]" value="<?php echo $single_manufacturer['manufacturer_id']; ?>" checked="checked" />
														<label for="<?php echo $single_manufacturer['manufacturer_id']; ?>"><?php echo $single_manufacturer['name']; ?></label>
														<?php } else { ?>
														<input class="checkbox" id="<?php echo $single_manufacturer['manufacturer_id']; ?>" type="checkbox" name="manufacturer_bought[]" value="<?php echo $single_manufacturer['manufacturer_id']; ?>" />
														<label for="<?php echo $single_manufacturer['manufacturer_id']; ?>"><?php echo $single_manufacturer['name']; ?></label>
													<?php } ?>
												</div>
											<?php } ?>
										</div>
										<a class="select_all" onclick="$(this).parent().find(':checkbox').attr('checked', true);">Выделить всё</a><a class="remove_selection" onclick="$(this).parent().find(':checkbox').attr('checked', false);">Снять выделение</a>
									</td>
									<td class="left">
										<div class="scrollbox" style="height: 400px; overflow-x: auto; width: 100%;">
											<?php $class = 'odd'; ?>
											<?php foreach ($categories as $category) { ?>
												<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
												<div class="<?php echo $class; ?>">
													<?php if (in_array($category['category_id'], $category_view)) { ?>
														<input class="checkbox" id="<?php echo $category['category_id']; ?>" type="checkbox" name="category_view[]" value="<?php echo $category['category_id']; ?>" checked="checked" />
														<label for="<?php echo $category['category_id']; ?>"><?php echo $category['name']; ?></label>
														<?php } else { ?>
														<input class="checkbox" id="<?php echo $category['category_id']; ?>" type="checkbox" name="category_view[]" value="<?php echo $category['category_id']; ?>" />
														<label for="<?php echo $category['category_id']; ?>"><?php echo $category['name']; ?></label>
													<?php } ?>																									
												</div>
											<?php } ?>
										</div>
										<a class="select_all" onclick="$(this).parent().find(':checkbox').attr('checked', true);">Выделить всё</a><a class="remove_selection" onclick="$(this).parent().find(':checkbox').attr('checked', false);">Снять выделение</a>
									</td>
									<td class="left">
										<div class="scrollbox" style="height: 400px; overflow-x: auto; width: 100%;">
											<?php $class = 'odd'; ?>
											<?php foreach ($categories as $category) { ?>
												<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
												<div class="<?php echo $class; ?>">
													<?php if (in_array($category['category_id'], $category_bought)) { ?>
														<input class="checkbox" id="category_<?php echo $category['category_id']; ?>" type="checkbox" name="category_bought[]" value="<?php echo $category['category_id']; ?>" checked="checked" />
														<label for="category_<?php echo $category['category_id']; ?>"><?php echo $category['name']; ?></label>
														<?php } else { ?>
														<input class="checkbox" id="category_<?php echo $category['category_id']; ?>" type="checkbox" name="category_bought[]" value="<?php echo $category['category_id']; ?>" />
														<label for="category_<?php echo $category['category_id']; ?>"><?php echo $category['name']; ?></label>
													<?php } ?>																									
												</div>
											<?php } ?>
										</div>
										<a class="select_all" onclick="$(this).parent().find(':checkbox').attr('checked', true);">Выделить всё</a><a class="remove_selection" onclick="$(this).parent().find(':checkbox').attr('checked', false);">Снять выделение</a>
									</td>
									<td class="left" style="vertical-align: top;">
										<div class="scrollbox" style="height: 400px;">
											<?php $class = 'odd'; foreach ($first_order_sources as $single_first_order_source) { ?>
												<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
												<div class="<?php echo $class; ?>">
													<?php if (in_array($single_first_order_source['first_order_source'], $first_order_source)) { ?>
														<input class="checkbox" id="<?php echo $single_first_order_source['first_order_source']; ?>" type="checkbox" name="first_order_source[]" value="<?php echo $single_first_order_source['first_order_source']; ?>" checked="checked" />
														<label for="<?php echo $single_first_order_source['first_order_source']; ?>"><?php echo $single_first_order_source['first_order_source']; ?></label>
														<?php } else { ?>
														<input class="checkbox" id="<?php echo $single_first_order_source['first_order_source']; ?>" type="checkbox" name="first_order_source[]" value="<?php echo $single_first_order_source['first_order_source']; ?>" />
														<label for="<?php echo $single_first_order_source['first_order_source']; ?>"><?php echo $single_first_order_source['first_order_source']; ?></label>
													<?php } ?>
												</div>
											<?php } ?>
										</div>
										<a class="select_all" onclick="$(this).parent().find(':checkbox').attr('checked', true);">Выделить всё</a><a class="remove_selection" onclick="$(this).parent().find(':checkbox').attr('checked', false);">Снять выделение</a>
									</td>
								</tr>
							</table>
						</td>
					</tr>					
					<tr>
						<td>
							<table class="list" border="0" style="border:0px; width:100%;">
								<thead>
									<tr>
										<th colspan="5" class="th_style"></th>
									</tr>
									<tr>
										<td class="left bordSTYLE">Настройки отображения</td>
									</tr>
									<tr style="height: 5px;"></tr>
								</thead>
								<tr>
									<td class="left" style="vertical-align:middle" valign="middle">
										<p>Описание:</p>
									<textarea name="description" cols="40" rows="3"><? echo $description; ?></textarea></td>
									<td class="left">
										<p>Цвет фона #</p>
										<input id="pick_status_bg_color" style="" type="text" name="bg_color" value="<? echo $bg_color; ?>" />									
									</td>
									<td class="left">
										<p>Цвет шрифта #</p>
										<input id="pick_status_txt_color" style="" type="text" name="txt_color" value="<? echo $txt_color; ?>" />									
									</td>
									<td class="left">
										<p>Иконка FontAwesome</p>
										<input id="fa_icon" style="" type="text" name="fa_icon" value="<? echo $fa_icon; ?>" /><? if ($fa_icon) { ?><i class="fa <? echo $fa_icon; ?>" aria-hidden="true"></i><? } ?>							
									</td>
									<td class="left">
										<p><?php echo $entry_sort_order; ?>:</p>
										<input type="text" name="sort_order" value="<?php echo $sort_order; ?>" size="1" />
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td>
							<a id="dynamics"></a>
							<table class="list" border="0" style="border:0px; width:100%;">
								<thead>
									<tr>
										<th colspan="2" class="th_style"></th>
									</tr>
									<tr>
										<td class="left bordSTYLE">Динамика сегмента</td>
									</tr>
									<tr style="height: 5px;"></tr>
								</thead>
								<tr>
									<td colspan="2" align="right" style="text-align:right;">
										Период статистики
										<select id="range" onchange="getSegmentChart(this.value)">
											<option value="week">За текущую неделю</option>
											<option value="month" selected="selected">За текущий месяц</option>
											<option value="year">За текущий год</option>
											<option value="last100">Последние 100 записей</option>		
										</select>
									</td>
								</tr>
								<tr>
									<td style="width:50%; padding:10px;">
										<div id="segment_dynamics_customer_count" style="width:100%; height:350px;"></div>
									</td>
									<td style="width:50%; padding:10px;">
										<div id="segment_dynamics_orders" style="width:100%; height:350px;"></div>
									</td>
								</tr>
								<tr>
									<td style="width:50%; padding:10px;">
										<div id="segment_dynamics_total_cheque" style="width:100%; height:350px;"></div>
									</td>
									<td style="width:50%; padding:10px;">
										<div id="segment_dynamics_avg_cheque" style="width:100%; height:350px;"></div>
									</td>
									
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</form>
		</div>
	</div>
</div>
<? if (isset($segment_id)) { ?>
	<script type="text/javascript" src="view/javascript/jquery/flot2/jquery.flot.js"></script> 
	<script type="text/javascript" src="view/javascript/jquery/flot2/jquery.flot.resize.min.js"></script>
	<script type="text/javascript"><!--
		function getSegmentChart(range) {
			$.ajax({
				type: 'get',
				url: 'index.php?route=sale/segments/getSegmentDynamicsChart&segment_id=<? echo $segment_id ?>&token=<?php echo $token; ?>&range=' + range,
				dataType: 'json',
				async: false,
				success: function(json) {
					var option = {	
						shadowSize: 0,
						colors: ['#4ea24e', '#1065D2'],
						bars: { 
							//	show: true,
							//	fill: true,
							lineWidth: 1
						},
						grid: {
							backgroundColor: '#FFFFFF',
							hoverable: true
						},
						points: {
							show: false
						},
						xaxis: {
							show: true,
							ticks: json['xaxis']
						},
						series: {            
							lines: {
								show: true,
								fill: true
							}
						}
					}
					
					$.plot($('#segment_dynamics_customer_count'), [json.customer_count], option);
					$.plot($('#segment_dynamics_total_cheque'), [json.total_cheque], option);
					$.plot($('#segment_dynamics_avg_cheque'), [json.avg_cheque], option);
					$.plot($('#segment_dynamics_orders'), [json.order_good_count, json.order_bad_count], option);
					
					$('#segment_dynamics_customer_count').bind('plothover', function(event, pos, item) {
						$('.tooltip').remove();
						
						if (item) {
							$('<div id="tooltip" class="tooltip top in"><div class="tooltip-arrow"></div><div class="tooltip-inner">' + item.datapoint[1].toFixed(2) + '</div></div>').prependTo('body');
							
							$('#tooltip').css({
								position: 'absolute',
								left: item.pageX - ($('#tooltip').outerWidth() / 2),
								top: item.pageY - $('#tooltip').outerHeight(),
								pointer: 'cursor'
							}).fadeIn('slow');	
							
							$('#segment_dynamics_customer_count').css('cursor', 'pointer');		
							} else {
							$('#segment_dynamics_customer_count').css('cursor', 'auto');
						}
					});
					
					$('#segment_dynamics_total_cheque').bind('plothover', function(event, pos, item) {
						$('.tooltip').remove();
						
						if (item) {
							$('<div id="tooltip" class="tooltip top in"><div class="tooltip-arrow"></div><div class="tooltip-inner">' + item.datapoint[1].toFixed(2) + '</div></div>').prependTo('body');
							
							$('#tooltip').css({
								position: 'absolute',
								left: item.pageX - ($('#tooltip').outerWidth() / 2),
								top: item.pageY - $('#tooltip').outerHeight(),
								pointer: 'cursor'
							}).fadeIn('slow');	
							
							$('#segment_dynamics_total_cheque').css('cursor', 'pointer');		
							} else {
							$('#segment_dynamics_total_cheque').css('cursor', 'auto');
						}
					});
					
					$('#segment_dynamics_orders').bind('plothover', function(event, pos, item) {
						$('.tooltip').remove();
						
						if (item) {
							$('<div id="tooltip" class="tooltip top in"><div class="tooltip-arrow"></div><div class="tooltip-inner">' + item.datapoint[1].toFixed(2) + '</div></div>').prependTo('body');
							
							$('#tooltip').css({
								position: 'absolute',
								left: item.pageX - ($('#tooltip').outerWidth() / 2),
								top: item.pageY - $('#tooltip').outerHeight(),
								pointer: 'cursor'
							}).fadeIn('slow');	
							
							$('#segment_dynamics_orders').css('cursor', 'pointer');		
							} else {
							$('#segment_dynamics_orders').css('cursor', 'auto');
						}
					});
					
					$('#segment_dynamics_avg_cheque').bind('plothover', function(event, pos, item) {
						$('.tooltip').remove();
						
						if (item) {
							$('<div id="tooltip" class="tooltip top in"><div class="tooltip-arrow"></div><div class="tooltip-inner">' + item.datapoint[1].toFixed(2) + '</div></div>').prependTo('body');
							
							$('#tooltip').css({
								position: 'absolute',
								left: item.pageX - ($('#tooltip').outerWidth() / 2),
								top: item.pageY - $('#tooltip').outerHeight(),
								pointer: 'cursor'
							}).fadeIn('slow');	
							
							$('#segment_dynamics_avg_cheque').css('cursor', 'pointer');		
							} else {
							$('#segment_dynamics_avg_cheque').css('cursor', 'auto');
						}
					});
					
				},
				error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		}
		
		$(document).ready(function(){
			getSegmentChart($('#range').val());		
		})
	//--></script> 	
<? } ?>
<script>
	
	function setBDay(no_year){
		if (no_year){
			//		$('#birthday_to').datepicker({dateFormat: 'mm-dd'});	
			//		$('#birthday_from').datepicker({dateFormat: 'mm-dd'});
			} else {
			//		$('#birthday_to').datepicker({dateFormat: 'yy-mm-dd'});	
			//		$('#birthday_from').datepicker({dateFormat: 'yy-mm-dd'});
		}
	}
	
	function setADate(no_year){
		if (no_year){
			//		$('#date_added_to').datepicker({dateFormat: 'mm-dd'});	
			//		$('#date_added_from').datepicker({dateFormat: 'mm-dd'});
			} else {
			//		$('#date_added_to').datepicker({dateFormat: 'yy-mm-dd'});	
			//		$('#date_added_from').datepicker({dateFormat: 'yy-mm-dd'});
		}	
	}
	
	//$('.date').datepicker({dateFormat: 'yy-mm-dd'});
	<? if (isset($segment_id)) { ?>
		$(document).ready(function(){
			<? if (isset($added_year) && $added_year) { ?>
				setADate(true);
				<? } else { ?>
				setADate(false);
			<? } ?>
			<? if (isset($birthday_year) && $birthday_year) { ?>
				setBDay(true);
				<? } else { ?>
				setBDay(false);
			<? } ?>
			
			$('#birthday_year').click(function(){
				setBDay(!$("#birthday_year").is(':checked'));
			});
			
			$('#added_year').click(function(){
				setBDay(!$("#added_year").is(':checked'));
			})
			
			$('#button_preload').click(function(){
				$('#preload').html("Подождите..");
				$('#preload').load('index.php?route=sale/segments/preloadSegmentCustomersAjax&segment_id=<? echo $segment_id; ?>&token=<? echo $token; ?>');	
			});
			
			$('#button_preload_sql').click(function(){
				$('#preload').html("Подождите..");
				$('#preload').load('index.php?route=sale/segments/preloadSegmentCustomersAjax&debug_sql=1&segment_id=<? echo $segment_id; ?>&token=<? echo $token; ?>');	
			});
		});
	<? } ?>
</script>
<?php echo $footer; ?>														
<div id="tab-upsale">

	<div style="padding:10px; margin-bottom:10px; border:1px dashed grey;">
		<h2><span style="color:#cf4a61;"><i class="fa fa-shopping-cart"></i> Настройки забытой корзины - воркер и таймлимит</span></h2>
		<table class="form">														
			<tr>
				<td style="width:33%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Включить логику</span></p>
					<select name="config_forgottencart_send_enable">
						<?php if ($config_forgottencart_send_enable) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>
				</td>
				<td style="width:33%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF"><i class="fa fa-clock-o"></i> Время работы</span></p>

					<input type="time" name="config_forgottencart_send_time_start" value="<?php echo $config_forgottencart_send_time_start; ?>" size="50" style="width:70px;" /> - 
					<input type="time" name="config_forgottencart_send_time_end" value="<?php echo $config_forgottencart_send_time_end; ?>" size="50" style="width:70px;" />
				</td>
				<td style="width:33%">
					<span class="help"><i class="fa fa-info-circle"></i> воркер запускается каждый час в рамках разрешенного времени и отбирает корзины, добавленные от "минимально часов" до "максимально часов" для первой итерации. для второй итерации - часы "от и до считаются от момента отправки уведомления в первой итерации". время в запросе считается от текущего времени запуска скрипта</span>
				</td>
			</tr>
		</table>

		<h2><span style="color:#FF7815;"><i class="fa fa-shopping-cart"></i> Настройки забытой корзины - первая итерация</span></h2>
		<table class="form">														
			<tr>
				<td style="width:10%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF7815; color:#FFF">Выполнять действие</span></p>
					<select name="config_forgottencart_send_something_1">
						<?php if ($config_forgottencart_send_something_1) { ?>
							<option value="1" selected="selected">Отправлять уведомление</option>
							<option value="0">Ничего не делать</option>
						<?php } else { ?>													
							<option value="1">Отправлять уведомление</option>
							<option value="0"  selected="selected">Ничего не делать</option>
						<? } ?>
					</select>
					<span class="help">первая итерация</span>
				</td>

				<td style="width:10%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF7815; color:#FFF">Код промокода</span></p>
					<input type="text" name="config_forgottencart_promocode_1" value="<?php echo $config_forgottencart_promocode_1; ?>" size="20" />
					<span class="help">скопировать из списка</span>
				</td>

				<td style="width:10%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF7815; color:#FFF">Отправлять Email</span></p>
					<select name="config_forgottencart_email_enable_1">
						<?php if ($config_forgottencart_email_enable_1) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>
					<span class="help">если указан мейл</span>
				</td>

				<td style="width:10%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF7815; color:#FFF">Отправлять SMS</span></p>
					<select name="config_forgottencart_sms_enable_1">
						<?php if ($config_forgottencart_sms_enable_1) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>
					<span class="help">если указан тел</span>
				</td>

				<td style="width:15%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF7815; color:#FFF">Simplecheckout AC</span></p>
					<select name="config_forgottencart_use_simplecheckout_carts_1">
						<?php if ($config_forgottencart_use_simplecheckout_carts_1) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>
					<span class="help">отбирать Abandoned Carts</span>
				</td>

				<td style="width:15%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF7815; color:#FFF">Потерянные заказы</span></p>
					<select name="config_forgottencart_use_zerostatus_orders_1">
						<?php if ($config_forgottencart_use_zerostatus_orders_1) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>
					<span class="help">отбирать заказы с нулевым статусом</span>
				</td>

				<td style="width:15%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF7815; color:#FFF">Минимально часов</span></p>
					<input type="number" step="1" name="config_forgottencart_time_min_hours_1" value="<?php echo $config_forgottencart_time_min_hours_1; ?>"  size="5"  />
					<span class="help">отбирать корзины от</span>
				</td>

				<td style="width:15%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF7815; color:#FFF">Максимально часов</span></p>
					<input type="number" step="1" name="config_forgottencart_time_max_hours_1" value="<?php echo $config_forgottencart_time_max_hours_1; ?>" size="5" />
					<span class="help">отбирать корзины до</span>
				</td>
			</tr>
		</table>

		<table class="form">
			<tr>
				<td style="width:20%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF7815; color:#FFF">Шаблон почты (модуль Emailtemplate)</span></p>
					<input type="text" name="config_forgottencart_email_template_1" value="<?php echo $config_forgottencart_email_template_1; ?>" size="50" />
					<span class="help">скопировать из списка шаблонов почты</span>
				</td>
				<td style="width:20%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF7815; color:#FFF">Шаблон почты (регулярные рассылки)</span></p>
					<select name="config_forgottencart_email_actiontemplate_1">
						<option value="0" <?php if (empty($config_forgottencart_email_actiontemplate_1)) { ?>selected="selected"<? } ?>>Не использовать</option>
						<?php foreach ($forgotten_actiontemplates as $forgotten_actiontemplate) { ?>
							<option value="<?php echo $forgotten_actiontemplate['actiontemplate_id']; ?>" <?php if ($config_forgottencart_email_actiontemplate_1 == $forgotten_actiontemplate['actiontemplate_id']) { ?>selected="selected"<? } ?>><?php echo $forgotten_actiontemplate['actiontemplate_title']; ?></option>
						<?php } ?>
					</select>
					<span class="help">выбрать из списка</span>
				</td>
				<td style="width:20%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF7815; color:#FFF">Трек-код</span></p>
					<input type="text" name="config_forgottencart_email_actiontemplate_tracking_code_1" value="<?php echo $config_forgottencart_email_actiontemplate_tracking_code_1; ?>" size="15" />
					<span class="help">найти в SEO/Трекинг</span>
				</td>
				<td style="width:40%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF7815; color:#FFF">Текст SMS</span></p>
					<input type="text" size="200" style="width:90%; font-size:16px; padding:5px;" name="config_forgottencart_sms_text_1" value="<?php echo $config_forgottencart_sms_text_1; ?>" />
					<span class="help"><b>{FIRSTNAME}, {PRODUCT}, {PROMOCODE}</b></span>
					<span class="help"><i class="fa fa-info-circle"></i> Если включена возможность отправки сообщений через Viber, настройки находятся в соответствующей вкладке</span>
				</td>
			</tr>	
		</table>

		<h2><span style="color:#7F00FF;"><i class="fa fa-shopping-cart"></i> Настройки забытой корзины - вторая итерация</span></h2>
		<table class="form">														
			<tr>
				<td style="width:10%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Выполнять действие</span></p>
					<select name="config_forgottencart_send_something_2">
						<?php if ($config_forgottencart_send_something_2) { ?>
							<option value="1" selected="selected">Отправлять уведомление</option>
							<option value="0">Ничего не делать</option>
						<?php } else { ?>													
							<option value="1">Отправлять уведомление</option>
							<option value="0"  selected="selected">Ничего не делать</option>
						<? } ?>
					</select>
					<span class="help">первая итерация</span>
				</td>

				<td style="width:10%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Код промокода</span></p>
					<input type="text" name="config_forgottencart_promocode_2" value="<?php echo $config_forgottencart_promocode_2; ?>" size="20" />
					<span class="help">скопировать из списка</span>
				</td>

				<td style="width:10%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Отправлять Email</span></p>
					<select name="config_forgottencart_email_enable_2">
						<?php if ($config_forgottencart_email_enable_2) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>
					<span class="help">если указан мейл</span>
				</td>

				<td style="width:10%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Отправлять SMS</span></p>
					<select name="config_forgottencart_sms_enable_2">
						<?php if ($config_forgottencart_sms_enable_2) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>
					<span class="help">если указан тел</span>
				</td>

				<td style="width:15%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Simplecheckout AC</span></p>
					<select name="config_forgottencart_use_simplecheckout_carts_2">
						<?php if ($config_forgottencart_use_simplecheckout_carts_2) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>
					<span class="help">отбирать Abandoned Carts</span>
				</td>

				<td style="width:15%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Потерянные заказы</span></p>
					<select name="config_forgottencart_use_zerostatus_orders_2">
						<?php if ($config_forgottencart_use_zerostatus_orders_2) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>
					<span class="help">отбирать заказы с нулевым статусом</span>
				</td>

				<td style="width:15%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Минимально часов</span></p>
					<input type="number" step="1" name="config_forgottencart_time_min_hours_2" value="<?php echo $config_forgottencart_time_min_hours_2; ?>"  size="5"  />
					<span class="help">отбирать корзины от</span>
				</td>

				<td style="width:15%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Максимально часов</span></p>
					<input type="number" step="1" name="config_forgottencart_time_max_hours_2" value="<?php echo $config_forgottencart_time_max_hours_2; ?>" size="5" />
					<span class="help">отбирать корзины до</span>
				</td>
			</tr>
		</table>

		<table class="form">
			<tr>
				<td style="width:20%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Шаблон почты (модуль Emailtemplate)</span></p>
					<input type="text" name="config_forgottencart_email_template_2" value="<?php echo $config_forgottencart_email_template_2; ?>" size="50" />
					<span class="help">скопировать из списка шаблонов почты</span>
				</td>
				<td style="width:20%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Шаблон почты (регулярные рассылки)</span></p>
					<select name="config_forgottencart_email_actiontemplate_2">
						<option value="0" <?php if (empty($config_forgottencart_email_actiontemplate_2)) { ?>selected="selected"<? } ?>>Не использовать</option>
						<?php foreach ($forgotten_actiontemplates as $forgotten_actiontemplate) { ?>
							<option value="<?php echo $forgotten_actiontemplate['actiontemplate_id']; ?>" <?php if ($config_forgottencart_email_actiontemplate_2 == $forgotten_actiontemplate['actiontemplate_id']) { ?>selected="selected"<? } ?>><?php echo $forgotten_actiontemplate['actiontemplate_title']; ?></option>
						<?php } ?>
					</select>
					<span class="help">выбрать из списка</span>
				</td>
				<td style="width:20%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Трек-код</span></p>
					<input type="text" name="config_forgottencart_email_actiontemplate_tracking_code_2" value="<?php echo $config_forgottencart_email_actiontemplate_tracking_code_2; ?>" size="15" />
					<span class="help">найти в SEO/Трекинг</span>
				</td>
				<td style="width:40%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Текст SMS</span></p>
					<input type="text" size="200" style="width:90%; font-size:16px; padding:5px;" name="config_forgottencart_sms_text_2" value="<?php echo $config_forgottencart_sms_text_2; ?>" />
					<span class="help"><b>{FIRSTNAME}, {PRODUCT}, {PROMOCODE}</b></span>
					<span class="help"><i class="fa fa-info-circle"></i> Если включена возможность отправки сообщений через Viber, настройки находятся в соответствующей вкладке</span>
				</td>
			</tr>	
		</table>
	</div>

	<div style="padding:10px; margin-bottom:10px; border:1px dashed grey;">
		<h2>Действие при успешном выполненнии первого заказа</h2>
		<table class="form">														
			<tr>
				<td style="width:25%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF7815; color:#FFF">Выполнять действие</span></p>
					<select name="config_firstorder_send_promocode">
						<?php if ($config_firstorder_send_promocode) { ?>
							<option value="1" selected="selected">Отправлять промокод</option>
							<option value="0">Ничего не делать</option>
						<?php } else { ?>													
							<option value="1">Отправлять промокод</option>
							<option value="0"  selected="selected">Ничего не делать</option>
						<? } ?>
					</select>
				</td>

				<td style="width:25%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF7815; color:#FFF">Код промокода</span></p>
					<input type="text" name="config_firstorder_promocode" value="<?php echo $config_firstorder_promocode; ?>" size="20" />
					<span class="help">код нужно скопировать из списка промокодов</span>
				</td>

				<td style="width:25%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF7815; color:#FFF">Отправлять почту</span></p>
					<select name="config_firstorder_email_enable">
						<?php if ($config_firstorder_email_enable) { ?>
							<option value="1" selected="selected">Да</option>
							<option value="0">Нет</option>
						<?php } else { ?>													
							<option value="1">Да</option>
							<option value="0"  selected="selected">Нет</option>
						<? } ?>
					</select>
					<span class="help">если у покупателя задан email</span>
				</td>

				<td style="width:25%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF7815; color:#FFF">Отправлять SMS</span></p>
					<select name="config_firstorder_sms_enable">
						<?php if ($config_firstorder_sms_enable) { ?>
							<option value="1" selected="selected">Да</option>
							<option value="0">Нет</option>
						<?php } else { ?>													
							<option value="1">Да</option>
							<option value="0"  selected="selected">Нет</option>
						<? } ?>
					</select>
					<span class="help">телефон покупателя есть в 100% случаев</span>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF7815; color:#FFF">Шаблон почты</span></p>
					<input type="text" name="config_firstorder_email_template" value="<?php echo $config_firstorder_email_template; ?>" size="50" />
					<span class="help">код нужно скопировать из списка шаблонов почты, например customer.firstorder_coupon</span>
				</td>
				<td colspan="2">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF7815; color:#FFF">Текст SMS</span></p>
					<input type="text" size="200" style="width:90%; font-size:16px; padding:5px;" name="config_firstorder_sms_text" value="<?php echo $config_firstorder_sms_text; ?>" />
					<span class="help"><b>{FIRSTNAME}, {PROMOCODE}</b></span>
					<span class="help"><i class="fa fa-info-circle"></i> Если включена возможность отправки сообщений через Viber, настройки находятся в соответствующей вкладке</span>
				</td>
			</tr>	
		</table>
	</div>

	<div style="padding:10px; margin-bottom:10px; border:1px dashed grey;">
		<h2>Бонусная система</h2>						
		<table class="form">								
			<tr>
				<td style="width:25%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Время жизни бонусов, дней</span></p>
					<input type="text" name="config_reward_lifetime" value="<?php echo $config_reward_lifetime; ?>" size="5" /> дней
				</td>

				<td style="width:25%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Максимальный процент</span></p>
					<input type="number" step="1" name="config_reward_maxsalepercent" value="<?php echo $config_reward_maxsalepercent; ?>" size="5" />%
					<span class="help">такое количество процентов от суммы заказа можно оплатить бонусами</span>
				</td>

				<td style="width:25%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Форматировать как валюту</span></p>
					<select name="rewardpoints_currency_mode">
						<?php if ($rewardpoints_currency_mode) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>	
					<span class="help">Да: 12 354.56 бонусов, Нет: 12454 бонуса</span>
				</td>

				<td style="width:25%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">SVG иконки бонусов</span></p>
					<input type="text" name="config_reward_logosvg" value="<?php echo $config_reward_logosvg; ?>" size="30" />
					<span class="help">/catalog/view/theme/default/img/money.svg</span>
				</td>
			</tr>

			<tr>								
				<td style="width:25%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Префикс бонусов</span></p>
					<input type="text" name="rewardpoints_currency_prefix" value="<?php echo $rewardpoints_currency_prefix; ?>" size="5" />
					<span class="help">$ 100500</span>
				</td>

				<td style="width:25%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Суффикс бонусов</span></p>
					<input type="text" name="rewardpoints_currency_suffix" value="<?php echo $rewardpoints_currency_suffix; ?>" size="5" />
					<span class="help">100500 $</span>
				</td>

				<td style="width:25%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF"><b>Процент начисления бонусов</b></span></p>
					<input type="number" step=".1" name="rewardpoints_pointspercent" value="<?php echo $rewardpoints_pointspercent; ?>" size="5" />
					<span class="help">значение по умолчанию, которое будет назначаться товарам, если не переназначено далее, в категория, брендах и коллекциях</span>
				</td>
			</tr>														
		</table>

		<h2>Уведомление о сгорании и начислении</h2>
		<table class="form">
			<tr>
				<td style="width:200px;">
					<span class="status_color" style="text-align: left; background: #51A62D; color: #FFF; ?>;">
						Уведомление о начислении бонусов
					</span>
				</td>
				<td style="width:50px" class="center">
					<input class="checkbox" type="checkbox" name="rewardpoints_added_sms_enable" id="rewardpoints_added_sms_enable"<?php if ($rewardpoints_added_sms_enable) { echo ' checked="checked"'; }?>/><label for="rewardpoints_added_sms_enable"></label>
				</td>
				<td style="padding:5px;">
					<input type="text" size="200" style="width:90%; font-size:16px; padding:5px;" name="rewardpoints_added_sms_text" value="<?php echo $rewardpoints_added_sms_text; ?>" />
					<span class="help"><b>{FIRSTNAME}, {POINTS_ADDED}, {POINTS_TOTAL}, {POINTS_ACTIVE_TO}</b></span>
				</td>
			</tr>
		</table>
		<table class="form">
			<tr>
				<td style="width:200px;">
					<span class="status_color" style="text-align: left; background: #ef5e67; color: #FFF; ?>;">
						Уведомление о сгорании бонусов
					</span>
				</td>
				<td style="width:50px" class="center">
					<input class="checkbox" type="checkbox" name="rewardpoints_reminder_enable" id="rewardpoints_reminder_enable"<?php if ($rewardpoints_reminder_enable) { echo ' checked="checked"'; }?>/><label for="rewardpoints_reminder_enable"></label>
				</td>
				<td style="padding:5px;">
					<input type="text" size="200" style="width:90%; font-size:16px; padding:5px;" name="rewardpoints_reminder_sms_text" value="<?php echo $rewardpoints_reminder_sms_text; ?>" />
					<span class="help"><b>{FIRSTNAME}, {POINTS_AMOUNT}, {POINTS_DAYS_LEFT}</b></span>
				</td>
			</tr>
		</table>
		<table class="form">
			<tr>
				<td style="width:33%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Отправлять за Х дней до сгорания</span></p>
					<input type="number" step="1" name="rewardpoints_reminder_days_left" value="<?php echo $rewardpoints_reminder_days_left; ?>" size="20" />
				</td>

				<td style="width:33%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Мин. сумма бонусов на счету</span></p>
					<input type="number" step="1" name="rewardpoints_reminder_min_amount" value="<?php echo $rewardpoints_reminder_min_amount; ?>" size="20" />
				</td>

				<td style="width:33%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Неиспользование бонусов Х дней</span></p>
					<input type="number" step="1" name="rewardpoints_reminder_days_noactive" value="<?php echo $rewardpoints_reminder_days_noactive; ?>" size="20" />
				</td>
			</tr>
		</table>

		<h2>Логика бонусов</h2>
		<table class="form">														
			<tr>
				<td style="width:20%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Оверлоад товаров</span></p>
					<select name="config_reward_overload_product">
						<?php if ($config_reward_overload_product) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>	
				</td>

				<td style="width:20%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Оверлоад коллекций</span></p>
					<select name="config_reward_overload_collection">
						<?php if ($config_reward_overload_collection) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>	
				</td>


				<td style="width:20%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Оверлоад брендов</span></p>
					<select name="config_reward_overload_manufacturer">
						<?php if ($config_reward_overload_manufacturer) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>	
				</td>

				<td style="width:20%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Оверлоад категорий</span></p>
					<select name="config_reward_overload_category">
						<?php if ($config_reward_overload_category) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>	
				</td>

			</tr>
			<tr>
				<td colspan="4">
					<i class="fa fa-exclamation-circle"></i> если в магазине не планируется использовать переназначения бонусов на каком-либо из этих этапов - их нужно отключить, это значительно ускоряет магазин
				</td>
			</tr>
		</table>

		<h2>Начисление бонусов</h2>
		<table class="form">														
			<tr>
				<td style="width:20%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Бонусов за установку приложения</span></p>
					<input type="number" step="1" name="rewardpoints_appinstall" value="<?php echo $rewardpoints_appinstall; ?>" size="10" />
					<span class="help">количество бонусов в национальной валюте, начисляемое при установке приложения, код <b>APPINSTALL_POINTS_ADD</b>,бонусы с этим кодом могут быть начислены только один раз одному покупателю</span>
				</td>

				<td style="width:20%">
					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Бонусов на день рождения</span></p>
						<input type="number" step="1"  name="rewardpoints_birthday" value="<?php echo $rewardpoints_birthday; ?>" size="10" />
						<span class="help">количество бонусов в национальной валюте, начисляемое на день рождения, код <b>BIRTHDAY_GREETING_REWARD</b>, бонусы с этим кодом могут быть начислены не чаще чем раз в 365 дней</span>
					</div>
					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Когда отправлять</span></p>
						<input type="number" step="1"  name="rewardpoints_birthday_days_to" value="<?php echo $rewardpoints_birthday_days_to; ?>" size="10" />
						<span class="help">+Х или -Х дней от дня рождения. 0 означает в тот же день</span>
					</div>
				</td>

				<td style="width:20%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Бонусов за отзыв</span></p>
					<input type="number" step="1"  name="rewardpoints_review" value="<?php echo $rewardpoints_review; ?>" size="5" />
					<span class="help">количество бонусов в национальной валюте, начисляемое за отзыв, код <b>REVIEW_WRITTEN_REWARD</b></span>
				</td>

				<td style="width:20%">
					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Минимальная длина отзыва</span></p>
						<input type="number" step="1"  name="rewardpoints_review_min_length" value="<?php echo $rewardpoints_review_min_length; ?>" size="10" />
						<span class="help">короче не считается</span>
					</div>

					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Фото в отзыве обязательно</span></p>
						<select name="rewardpoints_review_need_image">
							<?php if ($rewardpoints_review_need_image) { ?>
								<option value="1" selected="selected">Включить</option>
								<option value="0">Отключить</option>
							<?php } else { ?>													
								<option value="1">Включить</option>
								<option value="0"  selected="selected">Отключить</option>
							<? } ?>
						</select>	
						<span class="help">начислять бонусы только за отзывы с фото</span>
					</div>
				</td>

				<td style="width:20%">
					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Дней после публикации отзыва</span></p>
						<input type="number" step="1"  name="rewardpoints_review_days" value="<?php echo $rewardpoints_review_days; ?>" size="5" />
						<span class="help">через сколько дней начислять бонусы после одобрения</span>
					</div>

					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Покупка обязательна</span></p>
						<select name="rewardpoints_needs_purchased">
							<?php if ($rewardpoints_needs_purchased) { ?>
								<option value="1" selected="selected">Включить</option>
								<option value="0">Отключить</option>
							<?php } else { ?>													
								<option value="1">Включить</option>
								<option value="0"  selected="selected">Отключить</option>
							<? } ?>
						</select>	
						<span class="help">у клиента должен быть заказ с этим товаром</span>
					</div>
				</td>
			</tr>
		</table>
	</div>
</div>
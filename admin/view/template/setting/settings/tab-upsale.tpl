<div id="tab-upsale">

<h2>Настройки забытой корзины</h2>
<table class="form">														
		<tr>

		</tr>
</table>


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
			</td>
		</tr>	
	</table>

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
				<span class="help">/catalog/view/theme/kp/img/money.svg</span>
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

	<h2>Уведомление о сгорании</h2>
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
			<td style="width:50%">
				<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Бонусов за установку приложения</span></p>
				<input type="text" name="rewardpoints_appinstall" value="<?php echo $rewardpoints_appinstall; ?>" size="5" />
				<span class="help">количество бонусов в национальной валюте, начисляемое при установке приложения, код <b>APPINSTALL_POINTS_ADD</b>,бонусы с этим кодом могут быть начислены только один раз одному покупателю</span>
			</td>

			<td style="width:50%">
				<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Бонусов на день рождения</span></p>
				<input type="text" name="rewardpoints_birthday" value="<?php echo $rewardpoints_birthday; ?>" size="5" />
				<span class="help">количество бонусов в национальной валюте, начисляемое на день рождения, код <b>BIRTHDAY_GREETING_REWARD</b>, бонусы с этим кодом могут быть начислены не чаще чем раз в 365 дней</span>
			</td>
		</tr>
	</table>
</div>
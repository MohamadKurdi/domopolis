<div id="tab-option">
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
			<span class="help">количество бонусов в национальной валюте, начисляемое при установке приложения, код APPINSTALL_POINTS_ADD,бонусы с этим кодом могут быть начислены только один раз одному покупателю</span>
		</td>
		
		<td style="width:50%">
			<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Бонусов на день рождения</span></p>
			<input type="text" name="rewardpoints_birthday" value="<?php echo $rewardpoints_birthday; ?>" size="5" />
			<span class="help">количество бонусов в национальной валюте, начисляемое на день рождения, код BIRTHDAY_POINTS_ADD, бонусы с этим кодом могут быть начислены не чаще чем раз в 365 дней</span>
		</td>
	</tr>
</table>

<h2>Простое ценообразование</h2>
<table class="form">			
	<tr>
		<td style="width:50%">
			<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Включить простое ценообразование</span></p>
			<select name="config_enable_overprice">
				<?php if ($config_enable_overprice) { ?>
					<option value="1" selected="selected">Включить</option>
					<option value="0">Отключить</option>
				<?php } else { ?>													
					<option value="1">Включить</option>
					<option value="0"  selected="selected">Отключить</option>
				<? } ?>
			</select>	
		</td>
		<td style="width:50%">
			<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Наценка</span></p>
			<textarea name="config_overprice" cols="40" rows="5"><?php echo $config_overprice; ?></textarea>
			<span class="help">Для подсчета и анализа</span>
		</td>
	</tr>
</table>	

<h2><?php echo $text_items; ?></h2>
<table class="form">
	<tr>
		<td><span class="required">*</span> <?php echo $entry_catalog_limit; ?></td>
		<td><input type="text" name="config_catalog_limit" value="<?php echo $config_catalog_limit; ?>" size="3" />
			<?php if ($error_catalog_limit) { ?>
				<span class="error"><?php echo $error_catalog_limit; ?></span>
				<?php } ?></td>
			</tr>
			<tr>
				<td><span class="required">*</span> <?php echo $entry_admin_limit; ?></td>
				<td><input type="text" name="config_admin_limit" value="<?php echo $config_admin_limit; ?>" size="3" />
					<?php if ($error_admin_limit) { ?>
						<span class="error"><?php echo $error_admin_limit; ?></span>
						<?php } ?></td>
					</tr>
				</table>

				<h2><?php echo $text_voucher; ?></h2>
				<table class="form">
					<tr>
						<td><span class="required">*</span> <?php echo $entry_voucher_min; ?></td>
						<td><input type="text" name="config_voucher_min" value="<?php echo $config_voucher_min; ?>" />
							<?php if ($error_voucher_min) { ?>
								<span class="error"><?php echo $error_voucher_min; ?></span>
								<?php } ?></td>
							</tr>
							<tr>
								<td><span class="required">*</span> <?php echo $entry_voucher_max; ?></td>
								<td><input type="text" name="config_voucher_max" value="<?php echo $config_voucher_max; ?>" />
									<?php if ($error_voucher_max) { ?>
										<span class="error"><?php echo $error_voucher_max; ?></span>
										<?php } ?></td>
									</tr>
								</table>
								<h2><?php echo $text_tax; ?></h2>
								<table class="form">
									<tr>
										<td><?php echo $entry_tax; ?></td>
										<td><?php if ($config_tax) { ?>
											<input type="radio" name="config_tax" value="1" checked="checked" />
											<?php echo $text_yes; ?>
											<input type="radio" name="config_tax" value="0" />
											<?php echo $text_no; ?>
										<?php } else { ?>
											<input type="radio" name="config_tax" value="1" />
											<?php echo $text_yes; ?>
											<input type="radio" name="config_tax" value="0" checked="checked" />
											<?php echo $text_no; ?>
											<?php } ?></td>
										</tr>
										<tr>
											<td><?php echo $entry_vat; ?></td>
											<td><?php if ($config_vat) { ?>
												<input type="radio" name="config_vat" value="1" checked="checked" />
												<?php echo $text_yes; ?>
												<input type="radio" name="config_vat" value="0" />
												<?php echo $text_no; ?>
											<?php } else { ?>
												<input type="radio" name="config_vat" value="1" />
												<?php echo $text_yes; ?>
												<input type="radio" name="config_vat" value="0" checked="checked" />
												<?php echo $text_no; ?>
												<?php } ?></td>
											</tr>
											<tr>
												<td><?php echo $entry_tax_default; ?></td>
												<td><select name="config_tax_default">
													<option value=""><?php echo $text_none; ?></option>
													<?php  if ($config_tax_default == 'shipping') { ?>
														<option value="shipping" selected="selected"><?php echo $text_shipping; ?></option>
													<?php } else { ?>
														<option value="shipping"><?php echo $text_shipping; ?></option>
													<?php } ?>
													<?php  if ($config_tax_default == 'payment') { ?>
														<option value="payment" selected="selected"><?php echo $text_payment; ?></option>
													<?php } else { ?>
														<option value="payment"><?php echo $text_payment; ?></option>
													<?php } ?>
												</select></td>
											</tr>
											<tr>
												<td><?php echo $entry_tax_customer; ?></td>
												<td><select name="config_tax_customer">
													<option value=""><?php echo $text_none; ?></option>
													<?php  if ($config_tax_customer == 'shipping') { ?>
														<option value="shipping" selected="selected"><?php echo $text_shipping; ?></option>
													<?php } else { ?>
														<option value="shipping"><?php echo $text_shipping; ?></option>
													<?php } ?>
													<?php  if ($config_tax_customer == 'payment') { ?>
														<option value="payment" selected="selected"><?php echo $text_payment; ?></option>
													<?php } else { ?>
														<option value="payment"><?php echo $text_payment; ?></option>
													<?php } ?>
												</select></td>
											</tr>
										</table>
										<h2><?php echo $text_account; ?></h2>
										<table class="form">
											<tr>
												<td><?php echo $entry_customer_online; ?></td>
												<td><?php if ($config_customer_online) { ?>
													<input type="radio" name="config_customer_online" value="1" checked="checked" />
													<?php echo $text_yes; ?>
													<input type="radio" name="config_customer_online" value="0" />
													<?php echo $text_no; ?>
												<?php } else { ?>
													<input type="radio" name="config_customer_online" value="1" />
													<?php echo $text_yes; ?>
													<input type="radio" name="config_customer_online" value="0" checked="checked" />
													<?php echo $text_no; ?>
													<?php } ?></td>
												</tr>
												<tr>
													<td><?php echo $entry_customer_group; ?></td>
													<td><select name="config_customer_group_id">
														<?php foreach ($customer_groups as $customer_group) { ?>
															<?php if ($customer_group['customer_group_id'] == $config_customer_group_id) { ?>
																<option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
															<?php } else { ?>
																<option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
															<?php } ?>
														<?php } ?>
													</select></td>
												</tr>
												<tr>
													<td>Покупатели - мудаки<br /><span class='help'>Неблагонадежные покупатели</span></td>
													<td><select name="config_bad_customer_group_id">
														<?php foreach ($customer_groups as $customer_group) { ?>
															<?php if ($customer_group['customer_group_id'] == $config_bad_customer_group_id) { ?>
																<option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
															<?php } else { ?>
																<option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
															<?php } ?>
														<?php } ?>
													</select></td>
												</tr>
												<tr>
													<td>Группа, в которую попадают оптовики при регистрации</td>
													<td><select name="config_opt_group_id">
														<?php foreach ($customer_groups as $customer_group) { ?>
															<?php if ($customer_group['customer_group_id'] == $config_opt_group_id) { ?>
																<option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
															<?php } else { ?>
																<option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
															<?php } ?>
														<?php } ?>
													</select></td>
												</tr>
												<tr>
													<td><?php echo $entry_customer_group_display; ?></td>
													<td><div class="scrollbox">
														<?php $class = 'odd'; ?>
														<?php foreach ($customer_groups as $customer_group) { ?>
															<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
															<div class="<?php echo $class; ?>">
																<?php if (in_array($customer_group['customer_group_id'], $config_customer_group_display)) { ?>
																	<input id="<?php echo $customer_group['customer_group_id']; ?>" class="checkbox" type="checkbox" name="config_customer_group_display[]" value="<?php echo $customer_group['customer_group_id']; ?>" checked="checked" />
																	<label for="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?>
																	<?php } else { ?></label>
																	<input id="<?php echo $customer_group['customer_group_id']; ?>" class="checkbox" type="checkbox" name="config_customer_group_display[]" value="<?php echo $customer_group['customer_group_id']; ?>" />
																	<label for="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></label>
																<?php } ?>
															</div>
														<?php } ?>
													</div>
													<a class="select_all" onclick="$(this).parent().find(':checkbox').attr('checked', true);">Выделить всё</a><a class="remove_selection" onclick="$(this).parent().find(':checkbox').attr('checked', false);">Снять выделение</a>
													<?php if ($error_customer_group_display) { ?>
														<span class="error"><?php echo $error_customer_group_display; ?></span>
														<?php } ?></td>
													</tr>
													<tr>
														<td><?php echo $entry_customer_price; ?></td>
														<td><?php if ($config_customer_price) { ?>
															<input type="radio" name="config_customer_price" value="1" checked="checked" />
															<?php echo $text_yes; ?>
															<input type="radio" name="config_customer_price" value="0" />
															<?php echo $text_no; ?>
														<?php } else { ?>
															<input type="radio" name="config_customer_price" value="1" />
															<?php echo $text_yes; ?>
															<input type="radio" name="config_customer_price" value="0" checked="checked" />
															<?php echo $text_no; ?>
															<?php } ?></td>
														</tr>
														<tr>
															<td><?php echo $entry_account; ?></td>
															<td><select name="config_account_id">
																<option value="0"><?php echo $text_none; ?></option>
																<?php foreach ($informations as $information) { ?>
																	<?php if ($information['information_id'] == $config_account_id) { ?>
																		<option value="<?php echo $information['information_id']; ?>" selected="selected"><?php echo $information['title']; ?></option>
																	<?php } else { ?>
																		<option value="<?php echo $information['information_id']; ?>"><?php echo $information['title']; ?></option>
																	<?php } ?>
																<?php } ?>
															</select></td>
														</tr>
													</table>
													<h2><?php echo $text_checkout; ?></h2>
													<table class="form">
														<tr>
															<td><?php echo $entry_cart_weight; ?></td>
															<td><?php if ($config_cart_weight) { ?>
																<input type="radio" name="config_cart_weight" value="1" checked="checked" />
																<?php echo $text_yes; ?>
																<input type="radio" name="config_cart_weight" value="0" />
																<?php echo $text_no; ?>
															<?php } else { ?>
																<input type="radio" name="config_cart_weight" value="1" />
																<?php echo $text_yes; ?>
																<input type="radio" name="config_cart_weight" value="0" checked="checked" />
																<?php echo $text_no; ?>
																<?php } ?></td>
															</tr>
															<tr>
																<td><?php echo $entry_guest_checkout; ?></td>
																<td><?php if ($config_guest_checkout) { ?>
																	<input type="radio" name="config_guest_checkout" value="1" checked="checked" />
																	<?php echo $text_yes; ?>
																	<input type="radio" name="config_guest_checkout" value="0" />
																	<?php echo $text_no; ?>
																<?php } else { ?>
																	<input type="radio" name="config_guest_checkout" value="1" />
																	<?php echo $text_yes; ?>
																	<input type="radio" name="config_guest_checkout" value="0" checked="checked" />
																	<?php echo $text_no; ?>
																	<?php } ?></td>
																</tr>
																<tr>
																	<td><?php echo $entry_checkout; ?></td>
																	<td><select name="config_checkout_id">
																		<option value="0"><?php echo $text_none; ?></option>
																		<?php foreach ($informations as $information) { ?>
																			<?php if ($information['information_id'] == $config_checkout_id) { ?>
																				<option value="<?php echo $information['information_id']; ?>" selected="selected"><?php echo $information['title']; ?></option>
																			<?php } else { ?>
																				<option value="<?php echo $information['information_id']; ?>"><?php echo $information['title']; ?></option>
																			<?php } ?>
																		<?php } ?>
																	</select></td>
																</tr>
																<tr>
																	<td><?php echo $entry_order_edit; ?></td>
																	<td><input type="text" name="config_order_edit" value="<?php echo $config_order_edit; ?>" size="3" /></td>
																</tr>
																<tr>
																	<td><?php echo $entry_invoice_prefix; ?></td>
																	<td><input type="text" name="config_invoice_prefix" value="<?php echo $config_invoice_prefix; ?>" /></td>
																</tr>
																<tr>
																	<td><?php echo $entry_order_status; ?></td>
																	<td><select name="config_order_status_id">
																		<?php foreach ($order_statuses as $order_status) { ?>
																			<?php if ($order_status['order_status_id'] == $config_order_status_id) { ?>
																				<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
																			<?php } else { ?>
																				<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
																			<?php } ?>
																		<?php } ?>
																	</select></td>
																</tr>
																<tr>
																	<td>Статус "обработанного заказа"</td>
																	<td><select name="config_treated_status_id">
																		<?php foreach ($order_statuses as $order_status) { ?>
																			<?php if ($order_status['order_status_id'] == $config_treated_status_id) { ?>
																				<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
																			<?php } else { ?>
																				<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
																			<?php } ?>
																		<?php } ?>
																	</select></td>
																</tr>
																<tr>
																	<td>Статус "выполненного заказа"</td>
																	<td><select name="config_complete_status_id">
																		<?php foreach ($order_statuses as $order_status) { ?>
																			<?php if ($order_status['order_status_id'] == $config_complete_status_id) { ?>
																				<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
																			<?php } else { ?>
																				<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
																			<?php } ?>
																		<?php } ?>
																	</select></td>
																</tr>
																<tr>
																	<td>Статус "отмененного заказа"</td>
																	<td><select name="config_cancelled_status_id">
																		<?php foreach ($order_statuses as $order_status) { ?>
																			<?php if ($order_status['order_status_id'] == $config_cancelled_status_id) { ?>
																				<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
																			<?php } else { ?>
																				<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
																			<?php } ?>
																		<?php } ?>
																	</select></td>
																</tr>
																<tr>
																	<td>Статус "частично доставленного заказа"</td>
																	<td><select name="config_partly_delivered_status_id">
																		<?php foreach ($order_statuses as $order_status) { ?>
																			<?php if ($order_status['order_status_id'] == $config_partly_delivered_status_id) { ?>
																				<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
																			<?php } else { ?>
																				<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
																			<?php } ?>
																		<?php } ?>
																	</select></td>
																</tr>
																<tr>
																	<td>Статус "отмененного заказа" после отгрузки</td>
																	<td><select name="config_cancelled_after_status_id">
																		<?php foreach ($order_statuses as $order_status) { ?>
																			<?php if ($order_status['order_status_id'] == $config_cancelled_after_status_id) { ?>
																				<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
																			<?php } else { ?>
																				<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
																			<?php } ?>
																		<?php } ?>
																	</select></td>
																</tr>
																
																<tr>
																	<td>Статус заказа в пункте самовывоза<span class="help">для яндекс-маркета и уведомлений</span></td>
																	<td><select name="config_in_pickup_status_id">
																		<?php foreach ($order_statuses as $order_status) { ?>
																			<?php if ($order_status['order_status_id'] == $config_in_pickup_status_id) { ?>
																				<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
																			<?php } else { ?>
																				<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
																			<?php } ?>
																		<?php } ?>
																	</select></td>
																</tr>
																
																<tr>
																	<td>Статус заказа готовности к доставке<span class="help">для яндекс-маркета</span></td>
																	<td><select name="config_ready_to_delivering_status_id">
																		<?php foreach ($order_statuses as $order_status) { ?>
																			<?php if ($order_status['order_status_id'] == $config_ready_to_delivering_status_id) { ?>
																				<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
																			<?php } else { ?>
																				<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
																			<?php } ?>
																		<?php } ?>
																	</select></td>
																</tr>
																<tr>
																	<td>Статус заказа "доставляется"<span class="help">Для сканирования почты</span></td>
																	<td><select name="config_delivering_status_id">
																		<?php foreach ($order_statuses as $order_status) { ?>
																			<?php if ($order_status['order_status_id'] == $config_delivering_status_id) { ?>
																				<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
																			<?php } else { ?>
																				<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
																			<?php } ?>
																		<?php } ?>
																	</select></td>
																</tr>
																<tr>
																	<td>Статус после подверждения покупателем<span class="help">Статус, который будет установлен после подтверждения клиентом условий заказа по почте</span></td>
																	<td><select name="config_confirmed_order_status_id">
																		<?php foreach ($order_statuses as $order_status) { ?>
																			<?php if ($order_status['order_status_id'] == $config_confirmed_order_status_id) { ?>
																				<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
																			<?php } else { ?>
																				<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
																			<?php } ?>
																		<?php } ?>
																	</select></td>
																</tr>
																<tr>
																	<td>Статус после подверждения покупателем c оплатой<span class="help">Статус, который будет установлен после подтверждения клиентом условий заказа по почте при условии, что заказ необходимо оплачивать!</span></td>
																	<td><select name="config_confirmed_nopaid_order_status_id">
																		<?php foreach ($order_statuses as $order_status) { ?>
																			<?php if ($order_status['order_status_id'] == $config_confirmed_nopaid_order_status_id) { ?>
																				<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
																			<?php } else { ?>
																				<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
																			<?php } ?>
																		<?php } ?>
																	</select></td>
																</tr>
																<tr>
																	<td>Идентификаторы ОПЛАТЫ ПРИ ДОСТАВКЕ<span class="help">Идентификаторы способов оплат, при которых считается, что оплата будет произведена при доставке</span></td>
																	<td><input name="config_confirmed_delivery_payment_ids" type="text" style="width:700px;" value="<? echo $config_confirmed_delivery_payment_ids; ?>"></td>
																</tr>
																<tr>
																	<td>Оплаты - предоплаты!<span class="help">По этим идентификаторам определяется предоплата ли этот способ оплаты!</span></td>
																	<td><textarea name="config_confirmed_prepay_payment_ids" style="width:700px;" rows="4"><? echo $config_confirmed_prepay_payment_ids; ?></textarea></td>
																</tr>
																<tr>
																	<td>Статус после частичной оплаты<span class="help">Статус, который будет установлен после частичной оплаты</span></td>
																	<td><select name="config_prepayment_paid_order_status_id">
																		<?php foreach ($order_statuses as $order_status) { ?>
																			<?php if ($order_status['order_status_id'] == $config_prepayment_paid_order_status_id) { ?>
																				<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
																			<?php } else { ?>
																				<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
																			<?php } ?>
																		<?php } ?>
																	</select></td>
																</tr>
																<tr>
																	<td>Статус после полной оплаты<span class="help">Статус, который будет установлен после полной оплаты</span></td>
																	<td><select name="config_total_paid_order_status_id">
																		<?php foreach ($order_statuses as $order_status) { ?>
																			<?php if ($order_status['order_status_id'] == $config_total_paid_order_status_id) { ?>
																				<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
																			<?php } else { ?>
																				<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
																			<?php } ?>
																		<?php } ?>
																	</select></td>
																</tr>
																
																<tr>
																	<td>Фейловые причины отмены для бренд-менеджеров</td>
																	<td><div class="scrollbox" style="height:300px;">
																		<?php $class = 'odd'; ?>
																		<?php foreach ($reject_reasons as $reject_reason) { ?>
																			<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
																			<div class="<?php echo $class; ?>">
																				<?php if (in_array($reject_reason['reject_reason_id'], $config_brandmanager_fail_order_status_id)) { ?>
																					<input id="config_manager_confirmed_<?php echo $reject_reason['reject_reason_id']; ?>" class="checkbox" type="checkbox" name="config_brandmanager_fail_order_status_id[]" value="<?php echo $reject_reason['reject_reason_id']; ?>" checked="checked" />
																					<label for="config_manager_confirmed_<?php echo $reject_reason['reject_reason_id']; ?>"><?php echo $reject_reason['name']; ?></label>
																				<?php } else { ?>
																					<input id="config_manager_confirmed_<?php echo $reject_reason['reject_reason_id']; ?>" class="checkbox" type="checkbox" name="config_brandmanager_fail_order_status_id[]" value="<?php echo $reject_reason['reject_reason_id']; ?>" />
																					<label for="config_manager_confirmed_<?php echo $reject_reason['reject_reason_id']; ?>"><?php echo $reject_reason['name']; ?></label>
																				<?php } ?>
																			</div>
																		<?php } ?>
																	</div>
																	<a class="select_all" onclick="$(this).parent().find(':checkbox').attr('checked', true);">Выделить всё</a><a class="remove_selection" onclick="$(this).parent().find(':checkbox').attr('checked', false);">Снять выделение</a>
																</td>
															</tr>
															
															<tr>
																<td>"Подтвержденные статусы" для менеджера</td>
																<td><div class="scrollbox" style="height:300px;">
																	<?php $class = 'odd'; ?>
																	<?php foreach ($order_statuses as $order_status) { ?>
																		<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
																		<div class="<?php echo $class; ?>">
																			<?php if (in_array($order_status['order_status_id'], $config_manager_confirmed_order_status_id)) { ?>
																				<input id="config_manager_confirmed_<?php echo $order_status['order_status_id']; ?>" class="checkbox" type="checkbox" name="config_manager_confirmed_order_status_id[]" value="<?php echo $order_status['order_status_id']; ?>" checked="checked" />
																				<label for="config_manager_confirmed_<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></label>
																			<?php } else { ?>
																				<input id="config_manager_confirmed_<?php echo $order_status['order_status_id']; ?>" class="checkbox" type="checkbox" name="config_manager_confirmed_order_status_id[]" value="<?php echo $order_status['order_status_id']; ?>" />
																				<label for="config_manager_confirmed_<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></label>
																			<?php } ?>
																		</div>
																	<?php } ?>
																</div>
																<a class="select_all" onclick="$(this).parent().find(':checkbox').attr('checked', true);">Выделить всё</a><a class="remove_selection" onclick="$(this).parent().find(':checkbox').attr('checked', false);">Снять выделение</a>
															</td>
														</tr>
														<tr>
															<td>"Проблемные статусы" для менеджера</td>
															<td><div class="scrollbox" style="height:300px;">
																<?php $class = 'odd'; ?>
																<?php foreach ($order_statuses as $order_status) { ?>
																	<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
																	<div class="<?php echo $class; ?>">
																		<?php if (in_array($order_status['order_status_id'], $config_problem_order_status_id)) { ?>
																			<input id="config_problem_<?php echo $order_status['order_status_id']; ?>" class="checkbox" type="checkbox" name="config_problem_order_status_id[]" value="<?php echo $order_status['order_status_id']; ?>" checked="checked" />
																			<label for="config_problem_<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></label>
																		<?php } else { ?>
																			<input id="config_problem_<?php echo $order_status['order_status_id']; ?>" class="checkbox" type="checkbox" name="config_problem_order_status_id[]" value="<?php echo $order_status['order_status_id']; ?>" />
																			<label for="config_problem_<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></label>
																		<?php } ?>
																	</div>
																<?php } ?>
															</div>
															<a class="select_all" onclick="$(this).parent().find(':checkbox').attr('checked', true);">Выделить всё</a><a class="remove_selection" onclick="$(this).parent().find(':checkbox').attr('checked', false);">Снять выделение</a>
														</td>
													</tr>
													<tr>
														<td>Статусы для учета работы менеджера</td>
														<td><div class="scrollbox" style="height:300px;">
															<?php $class = 'odd'; ?>
															<?php foreach ($order_statuses as $order_status) { ?>
																<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
																<div class="<?php echo $class; ?>">
																	<?php if (in_array($order_status['order_status_id'], $config_problem_quality_order_status_id)) { ?>
																		<input id="config_problem_quality_<?php echo $order_status['order_status_id']; ?>" class="checkbox" type="checkbox" name="config_problem_quality_order_status_id[]" value="<?php echo $order_status['order_status_id']; ?>" checked="checked" />
																		<label for="config_problem_quality_<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></label>
																	<?php } else { ?>
																		<input id="config_problem_quality_<?php echo $order_status['order_status_id']; ?>" class="checkbox" type="checkbox" name="config_problem_quality_order_status_id[]" value="<?php echo $order_status['order_status_id']; ?>" />
																		<label for="config_problem_quality_<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></label>
																	<?php } ?>
																</div>
															<?php } ?>
														</div>
														<a class="select_all" onclick="$(this).parent().find(':checkbox').attr('checked', true);">Выделить всё</a><a class="remove_selection" onclick="$(this).parent().find(':checkbox').attr('checked', false);">Снять выделение</a>
													</td>
												</tr>
												<tr>
													<td>"Неподтвержденные статусы" для менеджера</td>
													<td><div class="scrollbox" style="height:300px;">
														<?php $class = 'odd'; ?>
														<?php foreach ($order_statuses as $order_status) { ?>
															<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
															<div class="<?php echo $class; ?>">
																<?php if (in_array($order_status['order_status_id'], $config_toapprove_order_status_id)) { ?>
																	<input id="config_toapprove_<?php echo $order_status['order_status_id']; ?>" class="checkbox" type="checkbox" name="config_toapprove_order_status_id[]" value="<?php echo $order_status['order_status_id']; ?>" checked="checked" />
																	<label for="config_toapprove_<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></label>
																<?php } else { ?>
																	<input id="config_toapprove_<?php echo $order_status['order_status_id']; ?>" class="checkbox" type="checkbox" name="config_toapprove_order_status_id[]" value="<?php echo $order_status['order_status_id']; ?>" />
																	<label for="config_toapprove_<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></label>
																<?php } ?>
															</div>
														<?php } ?>
													</div>
													<a class="select_all" onclick="$(this).parent().find(':checkbox').attr('checked', true);">Выделить всё</a><a class="remove_selection" onclick="$(this).parent().find(':checkbox').attr('checked', false);">Снять выделение</a>
												</td>
											</tr>
											<tr>
												<td>Выгрузка заказов в 1С. Только эти статусы</td>
												<td><div class="scrollbox" style="height:300px;">
													<?php $class = 'odd'; ?>
													<?php foreach ($order_statuses as $order_status) { ?>
														<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
														<div class="<?php echo $class; ?>">
															<?php if (in_array($order_status['order_status_id'], $config_odinass_order_status_id)) { ?>
																<input id="config_odinass_<?php echo $order_status['order_status_id']; ?>" class="checkbox" type="checkbox" name="config_odinass_order_status_id[]" value="<?php echo $order_status['order_status_id']; ?>" checked="checked" />
																<label for="config_odinass_<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></label>
															<?php } else { ?>
																<input id="config_odinass_<?php echo $order_status['order_status_id']; ?>" class="checkbox" type="checkbox" name="config_odinass_order_status_id[]" value="<?php echo $order_status['order_status_id']; ?>" />
																<label for="config_odinass_<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></label>
															<?php } ?>
														</div>
													<?php } ?>
												</div>
												<a class="select_all" onclick="$(this).parent().find(':checkbox').attr('checked', true);">Выделить всё</a><a class="remove_selection" onclick="$(this).parent().find(':checkbox').attr('checked', false);">Снять выделение</a>
											</td>
										</tr>
										<tr>
											<td>Статусы из которых нельзя удалять товар</td>
											<td><div class="scrollbox" style="height:300px;">
												<?php $class = 'odd'; ?>
												<?php foreach ($order_statuses as $order_status) { ?>
													<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
													<div class="<?php echo $class; ?>">
														<?php if (in_array($order_status['order_status_id'], $config_nodelete_order_status_id)) { ?>
															<input id="config_<?php echo $order_status['order_status_id']; ?>" class="checkbox" type="checkbox" name="config_nodelete_order_status_id[]" value="<?php echo $order_status['order_status_id']; ?>" checked="checked" />
															<label for="config_<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></label>
														<?php } else { ?>
															<input id="config_<?php echo $order_status['order_status_id']; ?>" class="checkbox" type="checkbox" name="config_nodelete_order_status_id[]" value="<?php echo $order_status['order_status_id']; ?>" />
															<label for="config_<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></label>
														<?php } ?>
													</div>
												<?php } ?>
											</div>
											<a class="select_all" onclick="$(this).parent().find(':checkbox').attr('checked', true);">Выделить всё</a><a class="remove_selection" onclick="$(this).parent().find(':checkbox').attr('checked', false);">Снять выделение</a>
										</td>
									</tr>
									<tr>
										<td>Статусы для подбора связки Amazon</td>
										<td><div class="scrollbox" style="height:300px;">
											<?php $class = 'odd'; ?>
											<?php foreach ($order_statuses as $order_status) { ?>
												<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
												<div class="<?php echo $class; ?>">
													<?php if (in_array($order_status['order_status_id'], $config_amazonlist_order_status_id)) { ?>
														<input id="config_amazonlist_<?php echo $order_status['order_status_id']; ?>" class="checkbox" type="checkbox" name="config_amazonlist_order_status_id[]" value="<?php echo $order_status['order_status_id']; ?>" checked="checked" />
														<label for="config_amazonlist_<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></label>
													<?php } else { ?>
														<input id="config_amazonlist_<?php echo $order_status['order_status_id']; ?>" class="checkbox" type="checkbox" name="config_amazonlist_order_status_id[]" value="<?php echo $order_status['order_status_id']; ?>" />
														<label for="config_amazonlist_<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></label>
													<?php } ?>
												</div>
											<?php } ?>
										</div>
										<a class="select_all" onclick="$(this).parent().find(':checkbox').attr('checked', true);">Выделить всё</a><a class="remove_selection" onclick="$(this).parent().find(':checkbox').attr('checked', false);">Снять выделение</a>
									</td>
								</tr>
							</table>
							<h2><?php echo $text_stock; ?></h2>
							<table class="form">
								<tr>
									<td><?php echo $entry_stock_display; ?></td>
									<td><?php if ($config_stock_display) { ?>
										<input type="radio" name="config_stock_display" value="1" checked="checked" />
										<?php echo $text_yes; ?>
										<input type="radio" name="config_stock_display" value="0" />
										<?php echo $text_no; ?>
									<?php } else { ?>
										<input type="radio" name="config_stock_display" value="1" />
										<?php echo $text_yes; ?>
										<input type="radio" name="config_stock_display" value="0" checked="checked" />
										<?php echo $text_no; ?>
										<?php } ?></td>
									</tr>
									<tr>
										<td><?php echo $entry_stock_warning; ?></td>
										<td><?php if ($config_stock_warning) { ?>
											<input type="radio" name="config_stock_warning" value="1" checked="checked" />
											<?php echo $text_yes; ?>
											<input type="radio" name="config_stock_warning" value="0" />
											<?php echo $text_no; ?>
										<?php } else { ?>
											<input type="radio" name="config_stock_warning" value="1" />
											<?php echo $text_yes; ?>
											<input type="radio" name="config_stock_warning" value="0" checked="checked" />
											<?php echo $text_no; ?>
											<?php } ?></td>
										</tr>
										<tr>
											<td><?php echo $entry_stock_checkout; ?></td>
											<td><?php if ($config_stock_checkout) { ?>
												<input type="radio" name="config_stock_checkout" value="1" checked="checked" />
												<?php echo $text_yes; ?>
												<input type="radio" name="config_stock_checkout" value="0" />
												<?php echo $text_no; ?>
											<?php } else { ?>
												<input type="radio" name="config_stock_checkout" value="1" />
												<?php echo $text_yes; ?>
												<input type="radio" name="config_stock_checkout" value="0" checked="checked" />
												<?php echo $text_no; ?>
												<?php } ?></td>
											</tr>
											<tr>
												<td>Статус по-умолчанию (есть на амазоне, или у поставщика)</td>
												<td><select name="config_stock_status_id">
													<?php foreach ($stock_statuses as $stock_status) { ?>
														<?php if ($stock_status['stock_status_id'] == $config_stock_status_id) { ?>
															<option value="<?php echo $stock_status['stock_status_id']; ?>" selected="selected"><?php echo $stock_status['name']; ?></option>
														<?php } else { ?>
															<option value="<?php echo $stock_status['stock_status_id']; ?>"><?php echo $stock_status['name']; ?></option>
														<?php } ?>
													<?php } ?>
												</select></td>
											</tr>
											<tr>
												<td>Статус, "есть у нас на складе"</td>
												<td><select name="config_in_stock_status_id">
													<?php foreach ($stock_statuses as $stock_status) { ?>
														<?php if ($stock_status['stock_status_id'] == $config_in_stock_status_id) { ?>
															<option value="<?php echo $stock_status['stock_status_id']; ?>" selected="selected"><?php echo $stock_status['name']; ?></option>
														<?php } else { ?>
															<option value="<?php echo $stock_status['stock_status_id']; ?>"><?php echo $stock_status['name']; ?></option>
														<?php } ?>
													<?php } ?>
												</select></td>
											</tr>
											<tr>
												<td>Статус, при котором нельзя купить</td>
												<td><select name="config_not_in_stock_status_id">
													<?php foreach ($stock_statuses as $stock_status) { ?>
														<?php if ($stock_status['stock_status_id'] == $config_not_in_stock_status_id) { ?>
															<option value="<?php echo $stock_status['stock_status_id']; ?>" selected="selected"><?php echo $stock_status['name']; ?></option>
														<?php } else { ?>
															<option value="<?php echo $stock_status['stock_status_id']; ?>"><?php echo $stock_status['name']; ?></option>
														<?php } ?>
													<?php } ?>
												</select></td>
											</tr>
											<tr>
												<td>Статус, при котором надо уточнить наличие</td>
												<td><select name="config_partly_in_stock_status_id">
													<?php foreach ($stock_statuses as $stock_status) { ?>
														<?php if ($stock_status['stock_status_id'] == $config_partly_in_stock_status_id) { ?>
															<option value="<?php echo $stock_status['stock_status_id']; ?>" selected="selected"><?php echo $stock_status['name']; ?></option>
														<?php } else { ?>
															<option value="<?php echo $stock_status['stock_status_id']; ?>"><?php echo $stock_status['name']; ?></option>
														<?php } ?>
													<?php } ?>
												</select></td>
											</tr>
										</table>
										<h2><?php echo $text_affiliate; ?></h2>
										<table class="form">
											<tr>
												<td><?php echo $entry_affiliate; ?></td>
												<td><select name="config_affiliate_id">
													<option value="0"><?php echo $text_none; ?></option>
													<?php foreach ($informations as $information) { ?>
														<?php if ($information['information_id'] == $config_affiliate_id) { ?>
															<option value="<?php echo $information['information_id']; ?>" selected="selected"><?php echo $information['title']; ?></option>
														<?php } else { ?>
															<option value="<?php echo $information['information_id']; ?>"><?php echo $information['title']; ?></option>
														<?php } ?>
													<?php } ?>
												</select></td>
											</tr>
											<tr>
												<td><?php echo $entry_commission; ?></td>
												<td><input type="text" name="config_commission" value="<?php echo $config_commission; ?>" size="3" /></td>
											</tr>
										</table>
										<h2><?php echo $text_return; ?></h2>
										<table class="form">
											<tr>
												<td><?php echo $entry_return; ?></td>
												<td>
													<select name="config_return_id">
														<option value="0"><?php echo $text_none; ?></option>
														<?php foreach ($informations as $information) { ?>
															<?php if ($information['information_id'] == $config_return_id) { ?>
																<option value="<?php echo $information['information_id']; ?>" selected="selected"><?php echo $information['title']; ?></option>
															<?php } else { ?>
																<option value="<?php echo $information['information_id']; ?>"><?php echo $information['title']; ?></option>
															<?php } ?>
														<?php } ?>
													</select>
												</td>
											</tr>
											<tr>
												<td><?php echo $entry_return_status; ?></td>
												<td><select name="config_return_status_id">
													<?php foreach ($return_statuses as $return_status) { ?>
														<?php if ($return_status['return_status_id'] == $config_return_status_id) { ?>
															<option value="<?php echo $return_status['return_status_id']; ?>" selected="selected"><?php echo $return_status['name']; ?></option>
														<?php } else { ?>
															<option value="<?php echo $return_status['return_status_id']; ?>"><?php echo $return_status['name']; ?></option>
														<?php } ?>
													<?php } ?>
												</select></td>
											</tr>
										</table>

										<h2>Другие привязки статей</h2>
										<table class="form">
											<tr>
												<td>Информация о бонусах</td>
												<td>
													<select name="config_reward_article_id">
														<option value="0"><?php echo $text_none; ?></option>
														<?php foreach ($informations as $information) { ?>
															<?php if ($information['information_id'] == $config_reward_article_id) { ?>
																<option value="<?php echo $information['information_id']; ?>" selected="selected"><?php echo $information['title']; ?></option>
															<?php } else { ?>
																<option value="<?php echo $information['information_id']; ?>"><?php echo $information['title']; ?></option>
															<?php } ?>
														<?php } ?>
													</select>
												</td>
											</tr>

											<tr>
												<td>Статья "как заказать"</td>
												<td>
													<select name="config_how_order_article_id">
														<option value="0"><?php echo $text_none; ?></option>
														<?php foreach ($informations as $information) { ?>
															<?php if ($information['information_id'] == $config_how_order_article_id) { ?>
																<option value="<?php echo $information['information_id']; ?>" selected="selected"><?php echo $information['title']; ?></option>
															<?php } else { ?>
																<option value="<?php echo $information['information_id']; ?>"><?php echo $information['title']; ?></option>
															<?php } ?>
														<?php } ?>
													</select>
												</td>
											</tr>

											<tr>
												<td>Информация о доставке</td>
												<td>
													<select name="config_delivery_article_id">
														<option value="0"><?php echo $text_none; ?></option>
														<?php foreach ($informations as $information) { ?>
															<?php if ($information['information_id'] == $config_delivery_article_id) { ?>
																<option value="<?php echo $information['information_id']; ?>" selected="selected"><?php echo $information['title']; ?></option>
															<?php } else { ?>
																<option value="<?php echo $information['information_id']; ?>"><?php echo $information['title']; ?></option>
															<?php } ?>
														<?php } ?>
													</select>
												</td>
											</tr>

											<tr>
												<td>Информация о оплате</td>
												<td>
													<select name="config_payment_article_id">
														<option value="0"><?php echo $text_none; ?></option>
														<?php foreach ($informations as $information) { ?>
															<?php if ($information['information_id'] == $config_payment_article_id) { ?>
																<option value="<?php echo $information['information_id']; ?>" selected="selected"><?php echo $information['title']; ?></option>
															<?php } else { ?>
																<option value="<?php echo $information['information_id']; ?>"><?php echo $information['title']; ?></option>
															<?php } ?>
														<?php } ?>
													</select>
												</td>
											</tr>

											<tr>
												<td>Информация о возвратах</td>
												<td>
													<select name="config_return_article_id">
														<option value="0"><?php echo $text_none; ?></option>
														<?php foreach ($informations as $information) { ?>
															<?php if ($information['information_id'] == $config_return_article_id) { ?>
																<option value="<?php echo $information['information_id']; ?>" selected="selected"><?php echo $information['title']; ?></option>
															<?php } else { ?>
																<option value="<?php echo $information['information_id']; ?>"><?php echo $information['title']; ?></option>
															<?php } ?>
														<?php } ?>
													</select>
												</td>
											</tr>

											<tr>
												<td>Информация о дискаунтах/скидках</td>
												<td>
													<select name="config_discounts_article_id">
														<option value="0"><?php echo $text_none; ?></option>
														<?php foreach ($informations as $information) { ?>
															<?php if ($information['information_id'] == $config_discounts_article_id) { ?>
																<option value="<?php echo $information['information_id']; ?>" selected="selected"><?php echo $information['title']; ?></option>
															<?php } else { ?>
																<option value="<?php echo $information['information_id']; ?>"><?php echo $information['title']; ?></option>
															<?php } ?>
														<?php } ?>
													</select>
												</td>
											</tr>

											<tr>
												<td>Информация о подарочных сертификатах</td>
												<td>
													<select name="config_present_certificates_article_id">
														<option value="0"><?php echo $text_none; ?></option>
														<?php foreach ($informations as $information) { ?>
															<?php if ($information['information_id'] == $config_present_certificates_article_id) { ?>
																<option value="<?php echo $information['information_id']; ?>" selected="selected"><?php echo $information['title']; ?></option>
															<?php } else { ?>
																<option value="<?php echo $information['information_id']; ?>"><?php echo $information['title']; ?></option>
															<?php } ?>
														<?php } ?>
													</select>
												</td>
											</tr>

											<tr>
												<td>Информация о магазине (О нас)</td>
												<td>
													<select name="config_about_article_id">
														<option value="0"><?php echo $text_none; ?></option>
														<?php foreach ($informations as $information) { ?>
															<?php if ($information['information_id'] == $config_about_article_id) { ?>
																<option value="<?php echo $information['information_id']; ?>" selected="selected"><?php echo $information['title']; ?></option>
															<?php } else { ?>
																<option value="<?php echo $information['information_id']; ?>"><?php echo $information['title']; ?></option>
															<?php } ?>
														<?php } ?>
													</select>
												</td>
											</tr>

											<tr>
												<td>Информация поставщикам</td>
												<td>
													<select name="config_vendors_article_id">
														<option value="0"><?php echo $text_none; ?></option>
														<?php foreach ($informations as $information) { ?>
															<?php if ($information['information_id'] == $config_vendors_article_id) { ?>
																<option value="<?php echo $information['information_id']; ?>" selected="selected"><?php echo $information['title']; ?></option>
															<?php } else { ?>
																<option value="<?php echo $information['information_id']; ?>"><?php echo $information['title']; ?></option>
															<?php } ?>
														<?php } ?>
													</select>
												</td>
											</tr>

											<tr>
												<td>Информация о кредитах</td>
												<td>
													<select name="config_credits_article_id">
														<option value="0"><?php echo $text_none; ?></option>
														<?php foreach ($informations as $information) { ?>
															<?php if ($information['information_id'] == $config_credits_article_id) { ?>
																<option value="<?php echo $information['information_id']; ?>" selected="selected"><?php echo $information['title']; ?></option>
															<?php } else { ?>
																<option value="<?php echo $information['information_id']; ?>"><?php echo $information['title']; ?></option>
															<?php } ?>
														<?php } ?>
													</select>
												</td>
											</tr>

											<tr>
												<td>Пользовательское соглашение</td>
												<td>
													<select name="config_agreement_article_id">
														<option value="0"><?php echo $text_none; ?></option>
														<?php foreach ($informations as $information) { ?>
															<?php if ($information['information_id'] == $config_agreement_article_id) { ?>
																<option value="<?php echo $information['information_id']; ?>" selected="selected"><?php echo $information['title']; ?></option>
															<?php } else { ?>
																<option value="<?php echo $information['information_id']; ?>"><?php echo $information['title']; ?></option>
															<?php } ?>
														<?php } ?>
													</select>
												</td>
											</tr>

											<tr>
												<td>Персональные данные</td>
												<td>
													<select name="config_personaldata_article_id">
														<option value="0"><?php echo $text_none; ?></option>
														<?php foreach ($informations as $information) { ?>
															<?php if ($information['information_id'] == $config_personaldata_article_id) { ?>
																<option value="<?php echo $information['information_id']; ?>" selected="selected"><?php echo $information['title']; ?></option>
															<?php } else { ?>
																<option value="<?php echo $information['information_id']; ?>"><?php echo $information['title']; ?></option>
															<?php } ?>
														<?php } ?>
													</select>
												</td>
											</tr>
										</table>
									</div>

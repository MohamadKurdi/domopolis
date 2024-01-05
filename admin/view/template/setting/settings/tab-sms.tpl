	<div id="tab-sms">
		<h2><i class="fa fa-cogs"></i> Настройки воркеров и очередей</h2>
		<table class="form">
			<tr>
				<td style="width:20%">
					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Воркер очереди SMS</span></p>
						<select name="config_sms_enable_queue_worker">
							<?php if ($config_sms_enable_queue_worker) { ?>
								<option value="1" selected="selected">Включить</option>
								<option value="0">Отключить</option>
							<?php } else { ?>													
								<option value="1">Включить</option>
								<option value="0"  selected="selected">Отключить</option>
							<? } ?>
						</select>
					</div>
					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF"><i class="fa fa-clock-o"></i> Время работы</span></p>

						<input type="time" name="config_sms_enable_queue_worker_time_start" value="<?php echo $config_sms_enable_queue_worker_time_start; ?>" size="50" style="width:70px;" /> - 
						<input type="time" name="config_sms_enable_queue_worker_time_end" value="<?php echo $config_sms_enable_queue_worker_time_end; ?>" size="50" style="width:70px;" />
					</div>										
				</td>	

				<td style="width:20%">
					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Библиотека SMS</span></p>
						<select name="config_smsgate_library">
							<?php foreach ($smsgates as $smsgate) { ?>
								<?php if ($smsgate == $config_smsgate_library) { ?>
									<option value="<?php echo $smsgate; ?>" selected="selected"><?php echo $smsgate; ?></option>
								<?php } else { ?>
									<option value="<?php echo $smsgate; ?>"><?php echo $smsgate; ?></option>
								<?php } ?>
							<?php } ?>				
						</select>
					</div>									
				</td>	

				<td style="width:20%">
					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Приоритет общей настройки смены статусов</span></p>
						<select name="config_sms_status_use_only_settings">
							<?php if ($config_sms_status_use_only_settings) { ?>
								<option value="1" selected="selected">Да</option>
								<option value="0">Нет</option>
							<?php } else { ?>													
								<option value="1">Да</option>
								<option value="0"  selected="selected">Нет</option>
							<? } ?>
						</select>
						<span class="help">Если включено, то режим отправки коротких уведомлений зависит от общей настройки статусов</span>
					</div>		
				</td>

				<td style="width:20%">
					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Библиотека поддерживает Viber</span></p>
						<select name="config_smsgate_library_enable_viber">
							<?php if ($config_smsgate_library_enable_viber) { ?>
								<option value="1" selected="selected">Да</option>
								<option value="0">Нет</option>
							<?php } else { ?>													
								<option value="1">Да</option>
								<option value="0"  selected="selected">Нет</option>
							<? } ?>
						</select>
					</div>	
				</td>	

				<td style="width:20%">
					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Отправлять всё через Viber + SMS</span></p>
						<select name="config_smsgate_library_enable_viber_fallback">
							<?php if ($config_smsgate_library_enable_viber_fallback) { ?>
								<option value="1" selected="selected">Да</option>
								<option value="0">Нет</option>
							<?php } else { ?>													
								<option value="1">Да</option>
								<option value="0"  selected="selected">Нет</option>
							<? } ?>
						</select>
					</div>	
				</td>						
			</tr>
		</table>

		<h2><i class="fa fa-search"></i> Аккаунт сервиса отправки SMS</h2>
		<table class="form">
			<tr>
				<td width="25%">
					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ef5e67; color:#FFF">Private Key</span></p>
						<input type="text" name="config_smsgate_api_key" value="<?php echo $config_smsgate_api_key; ?>" size="40" style="width:200px;" />	
					</div>
					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ef5e67; color:#FFF">Public Key</span></p>
						<input type="text" name="config_smsgate_secret_key" value="<?php echo $config_smsgate_secret_key; ?>" size="40" style="width:200px;" />	
					</div>
				</td>
				<td width="25%">
					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ef5e67; color:#FFF">USER</span></p>
						<input type="text" name="config_smsgate_user" value="<?php echo $config_smsgate_user; ?>" size="40" style="width:200px;" />	
					</div>
					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ef5e67; color:#FFF">PASSWD</span></p>
						<input type="text" name="config_smsgate_passwd" value="<?php echo $config_smsgate_passwd; ?>" size="40" style="width:200px;" />	
					</div>
				</td>
				<td width="25%">
					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ef5e67; color:#FFF">VIBER USER</span></p>
						<input type="text" name="config_smsgate_viber_auth_login" value="<?php echo $config_smsgate_viber_auth_login; ?>" size="40" style="width:200px;" />	
					</div>
					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ef5e67; color:#FFF">VIBER PWD</span></p>
						<input type="text" name="config_smsgate_viber_auth_pwd" value="<?php echo $config_smsgate_viber_auth_pwd; ?>" size="40" style="width:200px;" />	
					</div>
				</td>
				<td width="25%">
					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ef5e67; color:#FFF">SMS Альфа-имя</span></p>
						<input type="text" name="config_sms_from" value="<?php echo $config_sms_from; ?>" maxlength="15" style="width:200px;" />
					</div>
					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ef5e67; color:#FFF">SMS Альфа-имя (совместимость)</span></p>
						<input type="text" name="config_sms_sign" value="<?php echo $config_sms_sign; ?>"  maxlength="15" style="width:200px;" />
					</div>
					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ef5e67; color:#FFF">Viber Альфа-имя</span></p>
						<input type="text" name="config_viber_from" value="<?php echo $config_viber_from; ?>" maxlength="20" style="width:200px;" />
					</div>										
				</td>
			</tr>
		</table>

		<h2><i class="fa fa-mobile"></i> OTP авторизация</h2>
		<table class="form">
			<tr>
				<td width="20%">
					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff007f; color:#FFF">Включить OTP-авторизацию</span></p>
						<select name="config_otp_enable">
							<?php if ($config_otp_enable) { ?>
								<option value="1" selected="selected">Включить</option>
								<option value="0">Отключить</option>
							<?php } else { ?>													
								<option value="1">Включить</option>
								<option value="0"  selected="selected">Отключить</option>
							<? } ?>
						</select>
						<span class="help">One Time Password</span>
					</div>				
				</td>
				<td width="20%">
					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff007f; color:#FFF">Автоотключение</span></p>
						<select name="config_otp_auto_enable">
							<?php if ($config_otp_auto_enable) { ?>
								<option value="1" selected="selected">Включить</option>
								<option value="0">Отключить</option>
							<?php } else { ?>													
								<option value="1">Включить</option>
								<option value="0"  selected="selected">Отключить</option>
							<? } ?>
						</select>
						<span class="help">в зависимости от баланса шлюза</span>
					</div>				
				</td>
				<td width="20%">
					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff007f; color:#FFF">EMAIL OTP</span></p>
						<select name="config_otp_enable_email">
							<?php if ($config_otp_enable_email) { ?>
								<option value="1" selected="selected">Включить</option>
								<option value="0">Отключить</option>
							<?php } else { ?>													
								<option value="1">Включить</option>
								<option value="0"  selected="selected">Отключить</option>
							<? } ?>
						</select>
						<span class="help">не используется, настройка нужна для совместимости методов OTP</span>
					</div>
				</td>
				<td width="20%">
					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff007f; color:#FFF">SMS OTP</span></p>
						<select name="config_otp_enable_sms">
							<?php if ($config_otp_enable_sms) { ?>
								<option value="1" selected="selected">Включить</option>
								<option value="0">Отключить</option>
							<?php } else { ?>													
								<option value="1">Включить</option>
								<option value="0"  selected="selected">Отключить</option>
							<? } ?>
						</select>
						<span class="help">поддерживается автопрочтение кода OTP в современных браузерах</span>
					</div>
				</td>
				<td width="20%">
					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff007f; color:#FFF">VIBER OTP</span></p>
						<select name="config_otp_enable_viber">
							<?php if ($config_otp_enable_viber) { ?>
								<option value="1" selected="selected">Включить</option>
								<option value="0">Отключить</option>
							<?php } else { ?>													
								<option value="1">Включить</option>
								<option value="0"  selected="selected">Отключить</option>
							<? } ?>
						</select>
						<span class="help">только если библиотека отправки поддерживает viber. не поддерживается автопрочтение кода OTP.</span>
					</div>							
				</td>
			</tr>
		</table>
		<table class="form">
			<tr>
				<td style="width:50%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background: #ff007f; color: #FFF; ?>;">Текст SMS с OTP-кодом</span></p>
					<textarea name="config_sms_otp_text" rows="3" cols="100"><?php echo $config_sms_otp_text; ?></textarea>
					<span class="help"><b>{OTP_CODE}</b></span>
				</td>
			
				<td style="width:50%">
					<p><span class="status_color" style="display:inline-block;padding:3px 5px; background: #ff007f; color: #FFF; ?>;">Текст VIBER с OTP-кодом</span></p>
					<textarea name="config_viber_otp_text" rows="3" cols="100"><?php echo $config_viber_otp_text; ?></textarea>
					<span class="help"><b>{OTP_CODE}</b></span>
				</td>
			</tr>
		</table>

		<h2><i class="fa fa-mobile"></i> Восстановление пароля</h2>
		<table class="form">
			<tr>
				<td width="33%">
					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7f00; color:#FFF">Восстановление пароля по почте</span></p>
						<select name="config_restore_password_enable_email">
							<?php if ($config_restore_password_enable_email) { ?>
								<option value="1" selected="selected">Включить</option>
								<option value="0">Отключить</option>
							<?php } else { ?>													
								<option value="1">Включить</option>
								<option value="0"  selected="selected">Отключить</option>
							<? } ?>
						</select>
						<span class="help">Штатная логика восстановления пароля OpenCart</span>
					</div>
				</td>
				<td width="33%">
					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7f00; color:#FFF">Восстановление пароля по SMS</span></p>
						<select name="config_restore_password_enable_sms">
							<?php if ($config_restore_password_enable_sms) { ?>
								<option value="1" selected="selected">Включить</option>
								<option value="0">Отключить</option>
							<?php } else { ?>													
								<option value="1">Включить</option>
								<option value="0"  selected="selected">Отключить</option>
							<? } ?>
						</select>
						<span class="help">Отправлять новый пароль в SMS</span>
					</div>
				</td>
				<td width="33%">
					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7f00; color:#FFF">Восстановление пароля по VIBER</span></p>
						<select name="config_restore_password_enable_viber">
							<?php if ($config_restore_password_enable_viber) { ?>
								<option value="1" selected="selected">Включить</option>
								<option value="0">Отключить</option>
							<?php } else { ?>													
								<option value="1">Включить</option>
								<option value="0"  selected="selected">Отключить</option>
							<? } ?>
						</select>
						<span class="help">только если библиотека отправки поддерживает viber.</span>
					</div>							
				</td>
			</tr>
		</table>
		<table class="form">
			<tr>
				<td style="width:50%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background: #ff7f00; color: #FFF; ?>;">Текст SMS с новым паролем</span></p>
					<textarea name="config_sms_restore_password_text" rows="3" cols="100"><?php echo $config_sms_restore_password_text; ?></textarea>
					<span class="help"><b>{NEW_PASSWORD}</b></span>
				</td>
			
				<td style="width:50%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background: #ff7f00; color: #FFF; ?>;">Текст VIBER с новым паролем</span></p>
					<textarea name="config_viber_restore_password_text" rows="3" cols="100"><?php echo $config_viber_restore_password_text; ?></textarea>
					<span class="help"><b>{NEW_PASSWORD}</b></span>
				</td>
			</tr>
		</table>

		<h2>Уведомления клиента</h2>
		<table class="form">
			<tr>									
				<td style="width:15%">
					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ef5e67; color:#FFF">Уведомлять клиента о смене статуса</span></p>

						<select name="config_sms_send_new_order_status">
							<?php if ($config_sms_send_new_order_status) { ?>
								<option value="1" selected="selected">Включить</option>
								<option value="0">Отключить</option>
							<?php } else { ?>													
								<option value="1">Включить</option>
								<option value="0"  selected="selected">Отключить</option>
							<? } ?>
						</select>
					</div>
				</td>

				<td style="width:25%" class="left">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ef5e67; color:#FFF">Шаблонизация</span></p>
					<span class="help">											
						<b>{SNAME}</b> - название магазина<br />
						<b>{ID}</b> - номер заказа<br />
						<b>{DATE}</b> - дата заказа<br />
						<b>{TIME}</b> - время заказа<br />
						<b>{SUM}</b> - сумма заказа<br />
						<b>{STATUS}</b> - новый статус заказа<br />
						<b>{PHONE}</b> - телефон клиента<br />
						<b>{FIRSTNAME}</b> - имя клиента<br />
						<b>{LASTNAME}</b> - фамилия клиента<br />
						<b>{TTN}</b> - ТТН службы доставки<br />
						<b>{PAYMENT_INFO}</b> - данные платежа<br />
						<b>{PAYMENT_LINK}</b> - ссылка на оплату<br />
						<b>{PARTLY}</b> - инфо о частичной оплате<br />
						<b>{DELIVERY_SERVICE}</b> - служба доставки<br />
						<b>{POINTS_AMOUNT}</b> - количество бонусов на счету<br />
						<b>{POINTS_ADDED}</b> - бонусов добавлено<br />
						<b>{POINTS_ACTIVE_TO}</b> - дата активности добавленных бонусов<br />
						<b>{POINTS_DAYS_LEFT}</b> - осталось дней до сгорания бонусов
					</span>
				</td>

				<td style="width:25%" class="left">
					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ef5e67; color:#FFF">Маска телефона</span></p>
						<input type="text" name="config_phonemask" value="<?php echo $config_phonemask; ?>" size="20" />
					</div>
				</td>
			</tr>	
		</table>
		
		<table class="list">
			<tr>
				<td style="width:200px;">
					<span class="status_color" style="text-align: left; background: #43B02A; color:#fff">
						Уведомление о новом заказе
					</span>
				</td>
				<td style="width:50px" class="center">
					<input class="checkbox" type="checkbox" name="config_sms_send_new_order" id="config_sms_send_new_order" <?php if ($config_sms_send_new_order) { echo ' checked="checked"'; }?> />
					<label for="config_sms_send_new_order"></label>

				</td>
				<td style="padding:5px;">
					<input type="text" size="200" style="width:90%; font-size:16px; padding:5px;" name="config_sms_new_order_message" value="<?php echo $config_sms_new_order_message; ?>" />
				</td>
			</tr>

			<?php foreach ($order_statuses as $order_status) { ?>
				<?php $status_message = '';
				if (isset($config_sms_new_order_status_message[$order_status['order_status_id']])) {
					$status_message = $config_sms_new_order_status_message[$order_status['order_status_id']];
				} ?>
				<tr>
					<td style="width:200px;">
						<span class="status_color" style="text-align: left; background: #<?php echo !empty($order_status['status_bg_color']) ? $order_status['status_bg_color'] : ''; ?>; color: #<?php echo !empty($order_status['status_txt_color']) ? $order_status['status_txt_color'] : ''; ?>;">
							<?php echo $order_status['name']; ?>
						</span>
					</td>
					<td style="width:50px" class="center">
						<input data-key="config_sms_new_order_status_message" data-id="<?php echo $order_status['order_status_id']; ?>" data-name="enabled" class="checkbox" type="checkbox" name="config_sms_new_order_status_message[<?php echo $order_status['order_status_id']; ?>][enabled]" id="config_sms_new_order_status_message[<?php echo $order_status['order_status_id']; ?>][enabled]" <?php if (isset($status_message['enabled']) && $status_message['enabled']) { echo ' checked="checked"'; }?>/>

						<label for="config_sms_new_order_status_message[<?php echo $order_status['order_status_id']; ?>][enabled]"></label>

					</td>
					<td style="padding:5px;">
						<input data-key="config_sms_new_order_status_message" data-id="<?php echo $order_status['order_status_id']; ?>" data-name="message" type="text" size="200" style="width:90%; font-size:16px; padding:5px;" name="config_sms_new_order_status_message[<?php echo $order_status['order_status_id']; ?>][message]" value="<?php echo isset($status_message['message']) ? $status_message['message'] : ""; ?>" />
					</td>
				</tr>										
			<?php } ?>
			<tr>
				<td style="width:200px;">
					<span class="status_color" style="text-align: left; background: #43B02A; color: #FFF; ?>;">
						Трекинг отправки со склада
					</span>
				</td>
				<td style="width:50px" class="center">
					<input class="checkbox" type="checkbox" name="config_sms_tracker_leave_main_warehouse_enabled" id="config_sms_tracker_leave_main_warehouse_enabled"<?php if ($config_sms_tracker_leave_main_warehouse_enabled) { echo ' checked="checked"'; }?>/><label for="config_sms_tracker_leave_main_warehouse_enabled"></label>
				</td>
				<td style="padding:5px;">
					<input type="text" size="200" style="width:90%; font-size:16px; padding:5px;" name="config_sms_tracker_leave_main_warehouse" value="<?php echo $config_sms_tracker_leave_main_warehouse; ?>" />
				</td>
			</tr>

			<tr>
				<td style="width:200px;">
					<span class="status_color" style="text-align: left; background: #000; color: #FFF; ?>;">
						Успешная оплата
					</span>
				</td>
				<td style="width:50px" class="center">
					<input class="checkbox" type="checkbox" name="config_sms_payment_recieved_enabled" id="config_sms_payment_recieved_enabled"<?php if ($config_sms_payment_recieved_enabled) { echo ' checked="checked"'; }?>/><label for="config_sms_payment_recieved_enabled"></label>
				</td>
				<td style="padding:5px;">
					<input type="text" size="200" style="width:90%; font-size:16px; padding:5px;" name="config_sms_payment_recieved" value="<?php echo $config_sms_payment_recieved; ?>" />
				</td>
			</tr>
			
			<tr>
				<td style="width:200px;">
					<span class="status_color" style="text-align: left; background: #ef5e67; color: #FFF; ?>;">
						ТТН службы доставки: отправка
					</span>
				</td>
				<td style="width:50px" class="center">
					<input class="checkbox" type="checkbox" name="config_sms_ttn_sent_enabled" id="config_sms_ttn_sent_enabled"<?php if ($config_sms_ttn_sent_enabled) { echo ' checked="checked"'; }?>/><label for="config_sms_ttn_sent_enabled"></label>
				</td>
				<td style="padding:5px;">
					<input type="text" size="200" style="width:90%; font-size:16px; padding:5px;" name="config_sms_ttn_sent" value="<?php echo $config_sms_ttn_sent; ?>" />
				</td>
			</tr>

			<tr>
				<td style="width:200px;">
					<span class="status_color" style="text-align: left; background: #43B02A; color: #FFF; ?>;">
						ТТН службы доставки: доставлено
					</span>
				</td>
				<td style="width:50px" class="center">
					<input class="checkbox" type="checkbox" name="config_sms_ttn_ready_enabled" id="config_sms_ttn_ready_enabled"<?php if ($config_sms_ttn_ready_enabled) { echo ' checked="checked"'; }?>/><label for="config_sms_ttn_ready_enabled"></label>
				</td>
				<td style="padding:5px;">
					<input type="text" size="200" style="width:90%; font-size:16px; padding:5px;" name="config_sms_ttn_ready" value="<?php echo $config_sms_ttn_ready; ?>" />
				</td>
			</tr>

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
				</td>
			</tr>

			<tr>
				<td style="width:200px;">
					<span class="status_color" style="text-align: left; background: #51A62D; color: #FFF; ?>;">
						Уведомление о начислении бонусов
					</span>
				</td>
				<td style="width:50px" class="center">
					<input class="checkbox" type="checkbox" name="rewardpoints_added_sms_enable" id="rewardpoints_added_sms_enable"<?php if ($rewardpoints_added_sms_enable) { echo ' checked="checked"'; }?>/><label for="rewardpoints_reminder_enable"></label>
				</td>
				<td style="padding:5px;">
					<input type="text" size="200" style="width:90%; font-size:16px; padding:5px;" name="rewardpoints_added_sms_text" value="<?php echo $rewardpoints_added_sms_text; ?>" />
				</td>
			</tr>

			<tr>
				<td style="width:200px;">
					<span class="status_color" style="text-align: left; background: #ff7f00; color: #FFF; ?>;">
						SMS о ручной транзакции. Успешная оплата.
					</span>
				</td>
				<td style="width:50px" class="center">												
				</td>
				<td style="padding:5px;">
					<input type="text" size="200" style="width:90%; font-size:16px; padding:5px;" name="config_sms_transaction_text_type_1" value="<?php echo $config_sms_transaction_text_type_1; ?>" />
				</td>
			</tr>

			<tr>
				<td style="width:200px;">
					<span class="status_color" style="text-align: left; background: #ff7f00; color: #FFF; ?>;">
						SMS о ручной транзакции. Возврат безналичный
					</span>
				</td>
				<td style="width:50px" class="center">												
				</td>
				<td style="padding:5px;">
					<input type="text" size="200" style="width:90%; font-size:16px; padding:5px;" name="config_sms_transaction_text_type_2" value="<?php echo $config_sms_transaction_text_type_2; ?>" />
				</td>
			</tr>

			<tr>
				<td style="width:200px;">
					<span class="status_color" style="text-align: left; background: #ff7f00; color: #FFF; ?>;">
						SMS о ручной транзакции. Возврат остальное
					</span>
				</td>
				<td style="width:50px" class="center">												
				</td>
				<td style="padding:5px;">
					<input type="text" size="200" style="width:90%; font-size:16px; padding:5px;" name="config_sms_transaction_text_type_3" value="<?php echo $config_sms_transaction_text_type_3; ?>" />
				</td>
			</tr>

			<tr>
				<td style="width:200px;">
					<span class="status_color" style="text-align: left; background: #43B02A; color: #FFF; ?>;">
						Линк на оплату
					</span>
				</td>
				<td style="width:50px" class="center">
					<input class="checkbox" type="checkbox" name="config_sms_payment_link_enabled" id="config_sms_payment_link_enabled"<?php if ($config_sms_payment_link_enabled) { echo ' checked="checked"'; }?>/><label for="config_sms_payment_link_enabled"></label>
				</td>
				<td style="padding:5px;">
					<input type="text" size="200" style="width:90%; font-size:16px; padding:5px;" name="config_sms_payment_link" value="<?php echo $config_sms_payment_link; ?>" />
				</td>
			</tr>

			<tr>
				<td style="width:200px;">
					<span class="status_color" style="text-align: left; background: #43B02A; color: #FFF; ?>;">
						Поздравление с днем рождения
					</span>
				</td>
				<td style="width:50px" class="center">
					<input class="checkbox" type="checkbox" name="config_sms_birthday_greeting_enabled" id="config_sms_birthday_greeting_enabled"<?php if ($config_sms_birthday_greeting_enabled) { echo ' checked="checked"'; }?>/><label for="config_sms_birthday_greeting_enabled"></label>
				</td>
				<td style="padding:5px;">
					<input type="text" size="200" style="width:90%; font-size:16px; padding:5px;" name="config_sms_birthday_greeting" value="<?php echo $config_sms_birthday_greeting; ?>" />
				</td>
			</tr>
		</table>
	</div>
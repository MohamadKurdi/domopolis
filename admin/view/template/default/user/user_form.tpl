<?php echo $header; ?>
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
			<h1><img src="view/image/user.png" alt="" /> <?php echo $heading_title; ?></h1>
			<div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
		</div>
		<div class="content">
			<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
				<div style="width:50%; float:left;">
					<h2>Основная информация</h2>
					<table class="form">
						<tr>
							<td style="width:25%">
								<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF9900; color:#FFF"><?php echo $entry_username; ?></span></p>
								<input type="text" name="username" value="<?php echo $username; ?>" />

								<?php if ($error_username) { ?>
									<span class="error"><?php echo $error_username; ?></span>
								<?php } ?>
							</td>

							<td style="width:25%">
								<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF9900; color:#FFF"><?php echo $entry_firstname; ?></span></p>
								<input type="text" name="firstname" value="<?php echo $firstname; ?>" />

								<?php if ($error_firstname) { ?>
									<span class="error"><?php echo $error_firstname; ?></span>
								<?php } ?>
							</td>

							<td style="width:25%">
								<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF9900; color:#FFF"><?php echo $entry_lastname; ?></span></p>
								<input type="text" name="lastname" value="<?php echo $lastname; ?>" />
							</td>

							<td style="width:25%">
								<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF9900; color:#FFF"><?php echo $entry_email; ?></span></p>
								<input type="text" name="email" value="<?php echo $email; ?>" />
							</td>							
						</tr>
					</table>

					<h2>Пароль, активность</h2>
					<table class="form">
						<tr>		
							<td style="width:25%">
								<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF9900; color:#FFF"><?php echo $entry_user_group; ?></span></p>
								<select name="user_group_id">
									<?php foreach ($user_groups as $user_group) { ?>
										<?php if ($user_group['user_group_id'] == $user_group_id) { ?>
											<option value="<?php echo $user_group['user_group_id']; ?>" selected="selected"><?php echo $user_group['name']; ?></option>
										<?php } else { ?>
											<option value="<?php echo $user_group['user_group_id']; ?>"><?php echo $user_group['name']; ?></option>
										<?php } ?>
									<?php } ?>
								</select>
							</td>					
							<td style="width:25%">
								<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF"><?php echo $entry_status; ?></span></p>
								<select name="status">
									<?php if ($status) { ?>
										<option value="0"><?php echo $text_disabled; ?></option>
										<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
									<?php } else { ?>
										<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
										<option value="1"><?php echo $text_enabled; ?></option>
									<?php } ?>
								</select>
							</td>
							<td style="width:25%">
								<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF"><?php echo $entry_password; ?></span></p>
								<input type="password" name="password" value="<?php echo $password; ?>"  autocomplete='off' />
								<?php if ($error_password) { ?>
									<span class="error"><?php echo $error_password; ?></span>
								<?php  } ?>
							</td>						
							<td style="width:25%">
								<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF"><?php echo $entry_confirm; ?></span></p>
								<input type="password" name="confirm" value="<?php echo $confirm; ?>"  autocomplete='off' />
								<?php if ($error_confirm) { ?>
									<span class="error"><?php echo $error_confirm; ?></span>
								<?php  } ?>
							</td>
						</tr>
					</table>

					<h2>Дополнительные данные</h2>
					<table class="form">
						<tr>
							<td style="width:25%">
								<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Bitrix24 ID</span></p>
								<input type="text" name="bitrix_id" value="<?php echo $bitrix_id; ?>" />
								<span class="help">Если настроена интеграция с Bitrix24</span>
							</td>
							<td style="width:25%">
								<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Внутренний номер в АТС</span></p>
								<input type="text" name="internal_pbx_num" value="<?php echo $internal_pbx_num; ?>" />
								<span class="help">Если настроена интеграция с Asterisk</span>
							</td>
							<td style="width:25%">
								<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Auth Номер в АТС</span></p>
								<input type="text" name="internal_auth_pbx_num" value="<?php echo $internal_auth_pbx_num; ?>" placeholder="0000..."/>
								<span class="help">Если настроена интеграция с Asterisk</span>
							</td>
							<td style="width:25%">
								<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Внешний номер в АТС</span></p>
								<input type="text" name="outbound_pbx_num" value="<?php echo $outbound_pbx_num; ?>" />
								<span class="help">Если настроена интеграция с Asterisk</span>
							</td>
						</tr>
					</table>
				</div>
				<div style="width:45%; float:right; padding-left:10px; margin-left:10px; border-left:1px dashed grey;">
					<table class="list">
						<tr>
							<td colspan="3" class="left" style="color:#cf4a61;">
								<i class="fa fa-code"></i> <b>Расширенные права</b>
							</td>
						</tr>
						<tr>
							<td style="white-space: nowrap;color:#cf4a61;">
								<i class="fa fa-exclamation-circle"></i> <b>Супер-права</b>
							</td>
							<td style="width:220px;" class="center">
								<input id="is_av" type="checkbox" class="checkbox" name="is_av" <? if ($is_av){ ?> checked="checked" <? } ?> value="1" /><label for="is_av"></label>
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Добавляет некоторые расширенные права и отображение скрытых разделов
								</span>
							</td>
						</tr>						
						<tr>
							<td style="white-space: nowrap;color:#cf4a61;">
								<i class="fa fa-exclamation-circle"></i> <b>Больше статистики</b>
							</td>
							<td style="width:220px;" class="center">
								<input id="extended_stats" type="checkbox" class="checkbox" name="extended_stats" <? if ($extended_stats){ ?> checked="checked" <? } ?> value="1" /><label for="extended_stats"></label>
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Включает отображение большого количества статистики, отчетов, мониторинга, итд
								</span>
							</td>
						</tr>
						<tr>
							<td style="white-space: nowrap;color:#cf4a61;">
								<i class="fa fa-exclamation-circle"></i> <b>Фронт-девелопер</b>
							</td>
							<td style="width:220px;" class="center">
								<input id="dev_template" type="checkbox" class="checkbox" name="dev_template" <? if ($dev_template){ ?> checked="checked" <? } ?> value="1" /><label for="dev_template"></label>
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Если включено и пользователь залогинен в админку - на фронте используется шаблон для разработки
								</span>
							</td>
						</tr>
						<tr>
							<td style="white-space: nowrap;color:#cf4a61;">
								<i class="fa fa-exclamation-circle"></i> <b>Разблокировка заказов</b>
							</td>
							<td style="width:220px;" class="center">
								<input id="unlock_orders" type="checkbox" class="checkbox" name="unlock_orders" <? if ($unlock_orders){ ?> checked="checked" <? } ?> value="1" /><label for="unlock_orders"></label>
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Пользователю разрешено сбрасывать блокировку заказов после завершения или отмены для корректировки
								</span>
							</td>
						</tr>
						<tr>
							<td colspan="3" class="left" style="color:#7F00FF;">
								<i class="fa fa-eur"></i> <b>Роли в управлении продажами</b>
							</td>
						</tr>
						<tr>
							<td style="white-space: nowrap;color:#7F00FF;">
								<i class="fa fa-eur"></i> <b>Финансовые транзакции</b>
							</td>
							<td style="width:220px;" class="center">
								<input id="do_transactions" type="checkbox" class="checkbox" name="do_transactions" <? if ($do_transactions){ ?> checked="checked" <? } ?> value="1" /><label for="do_transactions"></label>
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Пользователю разрешено добавление и изменение транзакций вручную
								</span>
							</td>
						</tr>
						<tr>
							<td style="white-space: nowrap;color:#7F00FF;">
								<i class="fa fa-eur"></i> <b>Присваивать заказы</b>
							</td>
							<td style="width:220px;" class="center">
								<input id="own_orders" type="checkbox" class="checkbox" name="own_orders" <? if ($own_orders){ ?> checked="checked" <? } ?> value="1" /><label for="own_orders"></label>
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Только для менеджеров. Если включено - при первом открытии заказа пользователем заказ будет присвоен ему
								</span>
							</td>
						</tr>
						<tr>
							<td style="white-space: nowrap;color:#7F00FF;">
								<i class="fa fa-eur"></i> <b>Старший менеджер</b>
							</td>
							<td style="width:220px;" class="center">
								<input id="is_mainmanager" type="checkbox" class="checkbox" name="is_mainmanager" <? if ($is_mainmanager){ ?> checked="checked" <? } ?> value="1" /><label for="is_mainmanager"></label>
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Только для менеджеров. Расширяет права обработки заказов и отображает некоторые отчеты
								</span>
							</td>
						</tr>
						<tr>
							<td style="white-space: nowrap;color:#7F00FF;">
								<i class="fa fa-eur"></i> <b>Руководитель отдела продаж</b>
							</td>
							<td style="width:220px;" class="center">
								<input id="is_mainmanager" type="checkbox" class="checkbox" name="is_mainmanager" <? if ($is_mainmanager){ ?> checked="checked" <? } ?> value="1" /><label for="is_mainmanager"></label>
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Пользователь имеет другие KPI а также более расширенные права по сравнению с старшим менеджером
								</span>
							</td>
						</tr>
						<tr>
							<td style="white-space: nowrap;color:#7F00FF;">
								<i class="fa fa-eur"></i> <b>Редактор CSI</b>
							</td>
							<td style="width:220px;" class="center">
								<input id="edit_csi" type="checkbox" class="checkbox" name="edit_csi" <? if ($edit_csi){ ?> checked="checked" <? } ?> value="1" /><label for="edit_csi"></label>
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Пользователь имеет возможность редактировать параметры CSI в заказах
								</span>
							</td>
						</tr>

						<tr>
							<td colspan="3" class="left" style="color:#FF9900;">
								<i class="fa fa-clock-o"></i> <b>Контроль рабочего времени и задач</b>
							</td>
						</tr>
						<tr>
							<td style="white-space: nowrap;color:#FF9900;">
								<i class="fa fa-clock-o"></i> <b>Постановка задач</b>
							</td>
							<td style="width:220px;" class="center">
								<input id="ticket" type="checkbox" class="checkbox" name="ticket" <? if ($ticket){ ?> checked="checked" <? } ?> value="1" /><label for="ticket"></label>
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Включает внутренний таск-менеджер для пользователя
								</span>
							</td>
						</tr>
						<tr>
							<td style="white-space: nowrap;color:#FF9900;">
								<i class="fa fa-clock-o"></i> <b>Учет рабочего времени</b>
							</td>
							<td style="width:220px;" class="center">
								<input id="count_worktime" type="checkbox" class="checkbox" name="count_worktime" <? if ($count_worktime){ ?> checked="checked" <? } ?> value="1" /><label for="count_worktime"></label>
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Фиксирует входы и выходы
								</span>
							</td>
						</tr>
						<tr>
							<td style="white-space: nowrap;color:#FF9900;">
								<i class="fa fa-clock-o"></i> <b>Учет работы с контентом<</b>
							</td>
							<td style="width:220px;" class="center">
								<input id="count_content" type="checkbox" class="checkbox" name="count_content" <? if ($count_content){ ?> checked="checked" <? } ?> value="1" /><label for="count_content"></label>
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Фиксирует редактирования, создания и удаления сущностей
								</span>
							</td>
						</tr>
					</table>
				</div>
			</form>
		</div>
	</div>
</div>
<?php echo $footer; ?> 
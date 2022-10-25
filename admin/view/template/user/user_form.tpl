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
				<table class="form">
					<tr>
						<td><span class="required">*</span> <?php echo $entry_username; ?></td>
						<td><input type="text" name="username" value="<?php echo $username; ?>" />
							<?php if ($error_username) { ?>
								<span class="error"><?php echo $error_username; ?></span>
							<?php } ?></td>
					</tr>
					<tr>
						<td><span class="required">*</span> <?php echo $entry_firstname; ?></td>
						<td><input type="text" name="firstname" value="<?php echo $firstname; ?>" />
							<?php if ($error_firstname) { ?>
								<span class="error"><?php echo $error_firstname; ?></span>
							<?php } ?></td>
					</tr>
					<tr>
						<td><span class="required">*</span> <?php echo $entry_lastname; ?></td>
						<td><input type="text" name="lastname" value="<?php echo $lastname; ?>" />
							<?php if ($error_lastname) { ?>
								<span class="error"><?php echo $error_lastname; ?></span>
							<?php } ?></td>
					</tr>
					<tr>
						<td><?php echo $entry_email; ?></td>
						<td><input type="text" name="email" value="<?php echo $email; ?>" /></td>
					</tr>
					<tr>
                        <td>Гипер-права</td>
                        <td>
                            <select name="is_av">
                                <option <?php if ($is_av): ?>selected="selected"<?php endif?> value="1">
                                    Да
								</option>
                                <option <?php if (!$is_av): ?>selected="selected"<?php endif?> value="0">
                                    Нет
								</option>
							</select>
						</td>
					</tr>
					<tr>
                        <td>Разблокировка заказов</td>
                        <td>
                            <select name="unlock_orders">
                                <option <?php if ($unlock_orders): ?>selected="selected"<?php endif?> value="1">
                                    Да
								</option>
                                <option <?php if (!$unlock_orders): ?>selected="selected"<?php endif?> value="0">
                                    Нет
								</option>
							</select>
						</td>
					</tr>
					<tr>
                        <td>Финансовые транзакции</td>
                        <td>
                            <select name="do_transactions">
                                <option <?php if ($do_transactions): ?>selected="selected"<?php endif?> value="1">
                                    Да
								</option>
                                <option <?php if (!$do_transactions): ?>selected="selected"<?php endif?> value="0">
                                    Нет
								</option>
							</select>
						</td>
					</tr>
					<tr>
                        <td>Присваивать заказы?</td>
                        <td>
                            <select name="own_orders">
                                <option <?php if ($own_orders): ?>selected="selected"<?php endif?> value="1">
                                    Да
								</option>
                                <option <?php if (!$own_orders): ?>selected="selected"<?php endif?> value="0">
                                    Нет
								</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>Постановка задач</td>
						<td><select name="ticket">
							<option value="0" <? if (!$ticket) { ?>selected="selected"<? } ?>>Нет</option>
							<option value="1" <? if ($ticket) { ?>selected="selected"<? } ?>>Да</option>
						</select></td>           
					</tr>
					<tr>
						<td>Старший менеджер</td>
						<td><select name="is_mainmanager">
							<option value="0" <? if (!$is_mainmanager ) { ?>selected="selected"<? } ?>>Нет</option>
							<option value="1" <? if ($is_mainmanager ) { ?>selected="selected"<? } ?>>Да</option>
						</select></td>           
					</tr>
					<tr>
						<td>Руководитель отдела продаж</td>
						<td><select name="is_headsales">
							<option value="0" <? if (!$is_headsales ) { ?>selected="selected"<? } ?>>Нет</option>
							<option value="1" <? if ($is_headsales ) { ?>selected="selected"<? } ?>>Да</option>
						</select></td>           
					</tr>
					<tr>
						<td>Учет рабочего времени</td>
						<td><select name="count_worktime">
							<option value="0" <? if (!$count_worktime ) { ?>selected="selected"<? } ?>>Нет</option>
							<option value="1" <? if ($count_worktime) { ?>selected="selected"<? } ?>>Да</option>
						</select></td>           
					</tr>
					<tr>
						<td>Учет работы с контентом</td>
						<td><select name="count_content">
							<option value="0" <? if (!$count_content ) { ?>selected="selected"<? } ?>>Нет</option>
							<option value="1" <? if ($count_content) { ?>selected="selected"<? } ?>>Да</option>
						</select></td>           
					</tr>
					<tr>
						<td>Редактор CSI</td>
						<td><select name="edit_csi">
							<option value="0" <? if (!$edit_csi ) { ?>selected="selected"<? } ?>>Нет</option>
							<option value="1" <? if ($edit_csi) { ?>selected="selected"<? } ?>>Да</option>
						</select></td>           
					</tr>	
					<tr>
						<td>Bitrix24 ID</td>
						<td><input type="text" name="bitrix_id" value="<?php echo $bitrix_id; ?>" /></td>
					</tr>
					<tr>
						<td>Внутренний номер в АТС</td>
						<td><input type="text" name="internal_pbx_num" value="<?php echo $internal_pbx_num; ?>" /></td>
					</tr>
					<tr>
						<td>Внутренний номер в АТС для авторизации (0000...)</td>
						<td><input type="text" name="internal_auth_pbx_num" value="<?php echo $internal_auth_pbx_num; ?>" /></td>
					</tr>
					<tr>
						<td>Внешний номер в АТС</td>
						<td><input type="text" name="outbound_pbx_num" value="<?php echo $outbound_pbx_num; ?>" /></td>
					</tr>
					<tr>
						<td><?php echo $entry_user_group; ?></td>
						<td><select name="user_group_id">
							<?php foreach ($user_groups as $user_group) { ?>
								<?php if ($user_group['user_group_id'] == $user_group_id) { ?>
									<option value="<?php echo $user_group['user_group_id']; ?>" selected="selected"><?php echo $user_group['name']; ?></option>
									<?php } else { ?>
									<option value="<?php echo $user_group['user_group_id']; ?>"><?php echo $user_group['name']; ?></option>
								<?php } ?>
							<?php } ?>
						</select></td>
					</tr>
					<tr>
						<td><?php echo $entry_password; ?></td>
						<td><input type="password" name="password" value="<?php echo $password; ?>"  />
							<?php if ($error_password) { ?>
								<span class="error"><?php echo $error_password; ?></span>
							<?php  } ?></td>
					</tr>
					<tr>
						<td><?php echo $entry_confirm; ?></td>
						<td><input type="password" name="confirm" value="<?php echo $confirm; ?>" />
							<?php if ($error_confirm) { ?>
								<span class="error"><?php echo $error_confirm; ?></span>
							<?php  } ?></td>
					</tr>
					<tr>
						<td><?php echo $entry_status; ?></td>
						<td><select name="status">
							<?php if ($status) { ?>
								<option value="0"><?php echo $text_disabled; ?></option>
								<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
								<?php } else { ?>
								<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
								<option value="1"><?php echo $text_enabled; ?></option>
							<?php } ?>
						</select></td>
					</tr>
				</table>
			</form>
		</div>
	</div>
</div>
<?php echo $footer; ?> 
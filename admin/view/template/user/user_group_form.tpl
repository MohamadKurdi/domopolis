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
			<h1><img src="view/image/user-group.png" alt="" /><?php echo $heading_title; ?></h1>
			<div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
		</div>
		<div class="content">
			<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
				<table class="form">
					<tr>
						<td><span class="required">*</span> <?php echo $entry_name; ?></td>
						<td><input type="text" name="name" value="<?php echo $name; ?>" />
							<?php if ($error_name) { ?>
								<span class="error"><?php echo $error_name; ?></span>
							<?php  } ?>
						</td>
					</tr>
					<tr>
						<td>Суффикс шаблона меню</td>
						<td><input type="text" name="template_prefix" value="<?php echo $template_prefix; ?>" />  </td>          
					</tr>
					<tr>
						<td>Суффикс группы уведомлений</td>
						<td><input type="text" name="alert_namespace" value="<?php echo $alert_namespace; ?>" /> </td>           
					</tr>
					<tr>
						<td>Обслуживать магазины (менеджера)</td>
						<td>
							<div class="scrollbox" style="min-height: 150px;">
								<?php $class = 'even'; ?>
								<?php foreach ($stores as $store) { ?>
									<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
									<div class="<?php echo $class; ?>">
										<?php if (in_array($store['store_id'], $user_group_store)) { ?>
											<input id="store_<?php echo $store['store_id']; ?>" class="checkbox" type="checkbox" name="user_group_store[]" value="<?php echo $store['store_id']; ?>" checked="checked" />
											<label for="store_<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></label>
											<?php } else { ?>
											<input id="store_<?php echo $store['store_id']; ?>" class="checkbox" type="checkbox" name="user_group_store[]" value="<?php echo $store['store_id']; ?>" />
											<label for="store_<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></label>
										<?php } ?>
									</div>
								<?php } ?>
								
							</div>
							<a class="select_all" onclick="$(this).parent().find(':checkbox').attr('checked', true);">Выделить всё</a><a class="remove_selection" onclick="$(this).parent().find(':checkbox').attr('checked', false);">Снять выделение</a>
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
						<td>SIP очередь</td>
						<td><input type="text" name="sip_queue" value="<?php echo $sip_queue; ?>" /> </td>           
					</tr>
					
					<tr>
						<td>Bitrix24 id</td>
						<td><input type="text" name="bitrix_id" value="<?php echo $bitrix_id; ?>" /> </td>           
					</tr>
				</table>
				<table class="form">
					<tr>
						<td>
							<?php echo $entry_access; ?><br />
							<div class="scrollbox" style="min-height: 600px;">
								<?php $class = 'odd'; ?>
								<?php foreach ($permissions as $permission) { ?>
									<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
									<div class="<?php echo $class; ?>">
										<?php if (in_array($permission, $access)) { ?>
											<input id="access_<?php echo $permission; ?>" class="checkbox" type="checkbox" name="permission[access][]" value="<?php echo $permission; ?>" checked="checked" />
											<label for="access_<?php echo $permission; ?>"><?php echo $permission; ?></label>
											<?php } else { ?>
											<input id="access_<?php echo $permission; ?>" class="checkbox" type="checkbox" name="permission[access][]" value="<?php echo $permission; ?>" />
											<label for="access_<?php echo $permission; ?>"><?php echo $permission; ?></label>
										<?php } ?>
									</div>
								<?php } ?>
							</div>
							<a class="select_all" onclick="$(this).parent().find(':checkbox').attr('checked', true);"><?php echo $text_select_all; ?></a><a class="remove_selection" onclick="$(this).parent().find(':checkbox').attr('checked', false);"><?php echo $text_unselect_all; ?></a>
						</td>
						
						<td>
							<?php echo $entry_modify; ?><br />
							<div class="scrollbox" style="min-height: 600px;">
								<?php $class = 'odd'; ?>
								<?php foreach ($permissions as $permission) { ?>
									<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
									<div class="<?php echo $class; ?>">
										<?php if (in_array($permission, $modify)) { ?>
											<input id="modify_<?php echo $permission; ?>" class="checkbox" type="checkbox" name="permission[modify][]" value="<?php echo $permission; ?>" checked="checked" />
											<label for="modify_<?php echo $permission; ?>"><?php echo $permission; ?></label>
											<?php } else { ?>
											<input id="modify_<?php echo $permission; ?>" class="checkbox" type="checkbox" name="permission[modify][]" value="<?php echo $permission; ?>" />
											<label for="modify_<?php echo $permission; ?>"><?php echo $permission; ?></label>
										<?php } ?>
									</div>
								<?php } ?>
							</div>
							<a class="select_all" onclick="$(this).parent().find(':checkbox').attr('checked', true);"><?php echo $text_select_all; ?></a><a class="remove_selection" onclick="$(this).parent().find(':checkbox').attr('checked', false);"><?php echo $text_unselect_all; ?></a>
						</td>
					</tr>
				</table>
			</form>
		</div>
	</div>
</div>
<?php echo $footer; ?> 														
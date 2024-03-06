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
		<div class="heading">
			<h1><img src="view/image/payment.png" alt="" /> <?php echo $heading_title; ?></h1>
			<div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
		</div>
		<div class="content">
			<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
				<table class="form">
					<tr>
						<td><?php echo $entry_email; ?></td>
						<td><input type="text" name="concardis_email" value="<?php echo $concardis_email; ?>" />
							<?php if ($error_email) { ?>
								<span class="error"><?php echo $error_email; ?></span>
							<?php } ?></td>
					</tr>
					<tr>
						<td><?php echo $entry_secret; ?></td>
						<td><input type="text" name="concardis_secret" value="<?php echo $concardis_secret; ?>" /></td>
					</tr>
					<tr>
						<td><?php echo $entry_total; ?></td>
						<td><input type="text" name="concardis_total" value="<?php echo $concardis_total; ?>" /></td>
					</tr>
					<tr>
						<td><?php echo $entry_order_status; ?></td>
						<td><select name="concardis_order_status_id">
							<?php foreach ($order_statuses as $order_status) { ?>
								<?php if ($order_status['order_status_id'] == $concardis_order_status_id) { ?>
									<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
									<?php } else { ?>
									<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
								<?php } ?>
							<?php } ?>
						</select></td>
					</tr>
					<tr>
						<td><?php echo $entry_pending_status; ?></td>
						<td><select name="concardis_pending_status_id">
							<?php foreach ($order_statuses as $order_status) { ?>
								<?php if ($order_status['order_status_id'] == $concardis_pending_status_id) { ?>
									<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
									<?php } else { ?>
									<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
								<?php } ?>
							<?php } ?>
						</select></td>
					</tr>
					<tr>
						<td><?php echo $entry_canceled_status; ?></td>
						<td><select name="concardis_canceled_status_id">
							<?php foreach ($order_statuses as $order_status) { ?>
								<?php if ($order_status['order_status_id'] == $concardis_canceled_status_id) { ?>
									<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
									<?php } else { ?>
									<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
								<?php } ?>
							<?php } ?>
						</select></td>
					</tr>
					<tr>
						<td><?php echo $entry_failed_status; ?></td>
						<td><select name="concardis_failed_status_id">
							<?php foreach ($order_statuses as $order_status) { ?>
								<?php if ($order_status['order_status_id'] == $concardis_failed_status_id) { ?>
									<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
									<?php } else { ?>
									<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
								<?php } ?>
							<?php } ?>
						</select></td>
					</tr>
					<tr>
						<td><?php echo $entry_chargeback_status; ?></td>
						<td><select name="concardis_chargeback_status_id">
							<?php foreach ($order_statuses as $order_status) { ?>
								<?php if ($order_status['order_status_id'] == $concardis_chargeback_status_id) { ?>
									<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
									<?php } else { ?>
									<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
								<?php } ?>
							<?php } ?>
						</select></td>
					</tr>

					<tr>
						<td>Включить в магазинах</td>
						<td>
							<div class="scrollbox" style="min-height: 150px;">
								<?php $class = 'even'; ?>
								<?php foreach ($stores as $store) { ?>
									<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
									<div class="<?php echo $class; ?>">
										<?php if (in_array($store['store_id'], $concardis_store)) { ?>
											<input id="store_<?php echo $store['store_id']; ?>" class="checkbox" type="checkbox" name="concardis_store[]" value="<?php echo $store['store_id']; ?>" checked="checked" />
											<label for="store_<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></label>
											<?php } else { ?>
											<input id="store_<?php echo $store['store_id']; ?>" class="checkbox" type="checkbox" name="concardis_store[]" value="<?php echo $store['store_id']; ?>" />
											<label for="store_<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></label>
										<?php } ?>
									</div>
								<?php } ?>
								
							</div>
							<a class="select_all" onclick="$(this).parent().find(':checkbox').attr('checked', true);">Выделить всё</a><a class="remove_selection" onclick="$(this).parent().find(':checkbox').attr('checked', false);">Снять выделение</a>
						</td>
					</tr>
					
					<tr>
						<td><?php echo $entry_geo_zone; ?></td>
						<td><select name="concardis_geo_zone_id">
							<option value="0"><?php echo $text_all_zones; ?></option>
							<?php foreach ($geo_zones as $geo_zone) { ?>
								<?php if ($geo_zone['geo_zone_id'] == $concardis_geo_zone_id) { ?>
									<option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
									<?php } else { ?>
									<option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
								<?php } ?>
							<?php } ?>
						</select></td>
					</tr>
					<tr>
                        <td>Вторичный метод</td>
                        <td>
                            <select name="concardis_ismethod">
                                <option <?php if ($concardis_ismethod): ?>selected="selected"<?php endif?> value="1">
                                    <?=$text_enabled?>
								</option>
                                <option <?php if (!$concardis_ismethod): ?>selected="selected"<?php endif?> value="0">
                                    <?=$text_disabled?>
								</option>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $entry_status; ?></td>
						<td><select name="concardis_status">
							<?php if ($concardis_status) { ?>
								<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
								<option value="0"><?php echo $text_disabled; ?></option>
								<?php } else { ?>
								<option value="1"><?php echo $text_enabled; ?></option>
								<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
							<?php } ?>
						</select>
						</td>
					</tr>
					<tr>
						<td>Статус админки</td>
						<td><select name="concardis_status_fake">
							<?php if ($concardis_status_fake) { ?>
								<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
								<option value="0"><?php echo $text_disabled; ?></option>
								<?php } else { ?>
								<option value="1"><?php echo $text_enabled; ?></option>
								<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
							<?php } ?>
						</select></td>
					</tr>
					<tr>
						<td><?php echo $entry_sort_order; ?></td>
						<td><input type="text" name="concardis_sort_order" value="<?php echo $concardis_sort_order; ?>" size="3" /></td>
					</tr>
				</table>
			</form>
		</div>
	</div>
</div>
<?php echo $footer; ?> 
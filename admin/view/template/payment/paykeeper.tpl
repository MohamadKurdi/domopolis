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
			<h1><img src="view/image/payment.png" alt="" /> <?php echo $heading_title; ?></h1>
			<div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
		</div>
		<div class="content">
			<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
				<table class="form">
					<tr>
						<td><span class="required">*</span> <?php echo $entry_server_url; ?></td>
						<td><input type="text" name="paykeeper_server_url" value="<?php echo $paykeeper_server_url; ?>" />
							<?php if ($error_server_url) { ?>
								<span class="error"><?php echo $error_server_url; ?></span>
							<?php } ?></td>
					</tr>
					<tr>
						<td><span class="required">*</span> <?php echo $entry_secret_key; ?></td>
						<td><input type="text" name="paykeeper_secret_key" value="<?php echo $paykeeper_secret_key; ?>" />
							<?php if ($error_secret_key) { ?>
								<span class="error"><?php echo $error_secret_key; ?></span>
							<?php } ?></td>
					</tr>
					<tr>
						<td><?php echo $entry_order_status; ?></td>
						<td><select name="paykeeper_order_status_id">
							<?php foreach ($order_statuses as $order_status) { ?>
								<?php if ($order_status['order_status_id'] == $paykeeper_order_status_id) { ?>
									<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
									<?php } else { ?>
									<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
								<?php } ?>
							<?php } ?>
						</select></td>
					</tr>
					<tr>
                        <td>
                            Статус заказа после фэйла оплаты
                            <div class="help">
                                Если ПК вернет покупателя после неудачного платежа, заказу будет установлен выбранный статус. 
							</div>
						</td>
                        <td><select name="paykeeper_order_fail_status_id">
                            <?php foreach ($order_statuses as $order_status) { ?>
								<?php if ($order_status['order_status_id'] == $paykeeper_order_fail_status_id) { ?>
									<option value="<?php echo $order_status['order_status_id']; ?>"
                                    selected="selected"><?php echo $order_status['name']; ?></option>
									<?php } else { ?>
									<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
								<?php } ?>
							<?php } ?>
						</select></td>
					</tr>
					<tr>
                        <td>
                            Статус заказа для которого возможна отсроченная оплата
                            <div class="help">
								
							</div>
						</td>
                        <td><select name="paykeeper_order_later_status_id">
                            <?php foreach ($order_statuses as $order_status) { ?>
								<?php if ($order_status['order_status_id'] == $paykeeper_order_later_status_id) { ?>
									<option value="<?php echo $order_status['order_status_id']; ?>"
                                    selected="selected"><?php echo $order_status['name']; ?></option>
									<?php } else { ?>
									<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
								<?php } ?>
							<?php } ?>
						</select></td>
					</tr>
					<tr>
						<tr>
							<td>Вторичный метод</td>
							<td><select name="paykeeper_ismethod">
								<?php if ($paykeeper_ismethod) { ?>
									<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
									<option value="0"><?php echo $text_disabled; ?></option>
									<?php } else { ?>
									<option value="1"><?php echo $text_enabled; ?></option>
									<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
								<?php } ?>
							</select></td>
						</tr>
						<tr>
							<td>Статус фронта</td>
							<td><select name="paykeeper_status">
								<?php if ($paykeeper_status) { ?>
									<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
									<option value="0"><?php echo $text_disabled; ?></option>
									<?php } else { ?>
									<option value="1"><?php echo $text_enabled; ?></option>
									<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
								<?php } ?>
							</select></td>
						</tr>

						<tr>
							<td>Оплата на чекауте</td>
							<td><select name="paykeeper_pay_on_checkout">
								<?php if ($paykeeper_pay_on_checkout) { ?>
									<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
									<option value="0"><?php echo $text_disabled; ?></option>
									<?php } else { ?>
									<option value="1"><?php echo $text_enabled; ?></option>
									<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
								<?php } ?>
							</select></td>
						</tr>

						<tr>
							<td>Статус админки</td>
							<td><select name="paykeeper_status_fake">
								<?php if ($paykeeper_status_fake) { ?>
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
							<td><input type="text" name="paykeeper_sort_order" value="<?php echo $paykeeper_sort_order; ?>" size="1" /></td>
						</tr>
					</table>
				</form>
			</div>
		</div>
	</div>
	<?php echo $footer; ?>

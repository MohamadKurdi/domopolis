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
	<?php if ($success) { ?>
		<div class="success"><?php echo $success; ?></div>
	<?php } ?>
	<div class="box">
		<div class="heading order_head">
			<h1> <?php echo $heading_title; ?></h1>
			<div class="buttons"><a onclick="$('#form').attr('action', '<?php echo $update; ?>'); $('#form').submit();" class="button"><span><?php echo $status_done; ?></span></a><a onclick="$('form').submit();" class="button"><span><?php echo $button_delete; ?></span></a></div>
		</div>
		<div class="content">
			<form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
				<table class="list">
					<thead>
						<tr>
							<td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
							
							<td class="left" width="1"><?php if ($sort == 'call_id') { ?>
								<a href="<?php echo $sort_call_id; ?>" class="<?php echo strtolower($order); ?>">#</a>
								<?php } else { ?>
								<a href="<?php echo $sort_call_id; ?>">#</a>
							<?php } ?></td>
							<td class="left"><?php if ($sort == 'name') { ?>
								<a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
								<?php } else { ?>
								<a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
							<?php } ?></td>
							<td class="right" width="125px"><?php if ($sort == 'telephone') { ?>
								<a href="<?php echo $sort_telephone; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_telephone; ?></a>
								<?php } else { ?>
								<a href="<?php echo $sort_telephone; ?>"><?php echo $column_telephone; ?></a>
							<?php } ?></td>
							<td class="right">Товар</td>
							<td class="right">E-mail / Коммент. покупателя</td>
							<td class="right">Коммент. менеджера</td>
							<td class="right">Группа</td>
							<td class="right"><?php if ($sort == 'username') { ?>
								<a href="<?php echo $sort_username; ?>" class="<?php echo strtolower($order); ?>"><?php echo $text_manager; ?></a>
								<?php } else { ?>
								<a href="<?php echo $sort_username; ?>"><?php echo $text_manager; ?></a>
							<?php } ?></td>
							<td class="center"><?php echo $text_status; ?></td>
							<td class="right"><?php echo $text_added; ?></td>
							<td class="right"><?php echo $text_modified; ?></td>
							<td class="right"><?php echo $text_action; ?></td>
						</tr>
					</thead>
					<tbody>
						<?php if ($callbacks) { ?>
							<?php foreach ($callbacks as $callback) { ?>
								<tr>
									<td style="text-align: center;"><?php if ($callback['selected']) { ?>
										<input type="checkbox" name="selected[]" value="<?php echo $callback['callback_id']; ?>" checked="checked" />
										<?php } else { ?>
										<input type="checkbox" name="selected[]" value="<?php echo $callback['callback_id']; ?>" />
									<?php } ?></td>
									<td class="left"><?php echo $callback['callback_id']; ?></td>
									<td class="left">
										
										<? if ($callback['customer_href']) { ?>
											<a href='<?php echo $callback['customer_href']; ?>' target="_blank"><?php echo $callback['name']; ?></a>
											<? } else { ?>
											<?php echo $callback['name']; ?>
										<? } ?>
										
										<? if ($callback['is_missed']) { ?>
											<br /><span class="status_color_padding" style='margin-top:2px;color:white;background: #cf4a61;'><i class="fa fa-phone-square" aria-hidden="true"></i> Пропущенный</span>
											<? } elseif ($callback['is_cheaper']) { ?>
											<br /><span class="status_color_padding"style='margin-top:2px;color:white;background:#4ea24e;'><i class="fa fa-eye" aria-hidden="true"></i> Дешевле</span> 
											<? } else { ?>
											<br /><span class="status_color_padding"style='margin-top:2px;color:white;background:#e4c25a;'><i class="fa fa-phone-square" aria-hidden="true"></i> Обратный</span> 
										<? } ?>
										
									</td>
									
									<td class="left"><?php if ($callback['product']) {?><img src="<?php echo $callback['product']['image']; ?>" /><? } ?></td>
									
									<td class="right"><?php echo $callback['telephone']; ?><span class='click2call' data-phone="<?php echo $callback['telephone']; ?>"></span></td>
									<td class="left" style="max-width:200px;">
										<? if ($callback['email_buyer']) { ?>
											<div><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ccc;"><?php echo $callback['email_buyer']; ?></span></div>
										<? } ?>
										<div style="margin-top:5px;"><?php echo strip_tags($callback['comment_buyer']); ?></div>
										<? if ($callback['is_cheaper'] && $callback['product_id'] && $callback['product']) { ?>
											<div style="margin-top:5px;">
												<code><?php echo $callback['product']['name']; ?>, арт. <?php echo $callback['product']['sku']; ?></code>
											</div>
										<? } ?>
									</td>
									<td class="left" style="max-width:200px;"><?php echo strip_tags($callback['comment']); ?>
									</td> 
									<td class="left">
										<span class="status_color" style="display:inline-block; padding:3px 5px; background:#ccc;"><?php echo $callback['sip_group'] ?></span>
									</td> 
									<td class="right"><?php echo $callback['username']; ?></td>
									<?php if ($callback['status'] == $status_done) { ?>
										<td class="center">
											<span class="status_color_padding" style="background:#4ea24e;color: white;"><?php echo $callback['status']; ?></span>
											</td>
										<?php } else { ?>
										<td class="center">
											<span class="status_color_padding" style="background:#cf4a61;color: white;"><?php echo $callback['status']; ?></span>
											</td>
									<?php } ?>
									<td class="right" style="font-size:10px;"><?php echo $callback['date_added']; ?></td>
									<td class="right" style="font-size:10px;"><?php echo $callback['date_modified']; ?></td>
									<td class="right">
											<? if ($callback['is_my_group'] || $this->user->getIsAV()) { ?>
													<a class='button' href="<?php echo $callback['action']; ?>"><?php echo $text_edit; ?></a>
											<? } ?>
									</td>
								</tr>
							<?php } ?>
							<?php } else { ?>
							<tr>
								<td class="center" colspan="9"><?php echo $text_no_results; ?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</form>
			<div class="pagination"><?php echo $pagination; ?></div>
		</div>
	</div>
</div>
<?php echo $footer; ?>

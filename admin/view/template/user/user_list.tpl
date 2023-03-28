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
			<h1><img src="view/image/user.png" alt="" /> <?php echo $heading_title; ?></h1>
			<div class="buttons"><a href="<?php echo $insert; ?>" class="button"><?php echo $button_insert; ?></a><a onclick="$('form').submit();" class="button"><?php echo $button_delete; ?></a></div>
		</div>
		<div class="content">
			<form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
				<table class="list">
					<tbody>
						<?php if ($users) { ?>							
							<? foreach ($users as $name => $group) { ?>								
								<? if ($name == 'Уволенные') { ?>
									<tr>
										<td colspan="6" class="left"><b><? echo $name; ?></b> <span onclick="$('.hidden_del').toggle()" style="cursor:pointer; border-bottom:1px dashed black">развернуть</span></td>
									</td>
									<?php foreach ($group as $user) { ?>
										<tr style="display:none;" class="hidden_del">
											<td style="text-align: center;"><?php if ($user['selected']) { ?>
												<input type="checkbox" name="selected[]" value="<?php echo $user['user_id']; ?>" checked="checked" />
											<?php } else { ?>
												<input type="checkbox" name="selected[]" value="<?php echo $user['user_id']; ?>" />
												<?php } ?></td>
												<td class="left">
													<?php echo $user['username']; ?>
												</td>
												<td class="left">
													<?php echo $user['firstname']; ?> <?php echo $user['lastname']; ?>
												</td>	

												<td class="left">
													<? if ($user['status']) { ?>
														<i class="fa fa-check-circle" style="color:#4ea24e"></i>
													<? } else { ?>
														<i class="fa fa-times-circle" style="color:#cf4a61"></i>
													<? } ?>
												</td>

												<td class="left">
													<? if ($user['is_av']) { ?>
														<i class="fa fa-check-circle" style="color:#4ea24e"></i>
													<? } else { ?>
														<i class="fa fa-times-circle" style="color:#cf4a61"></i>
													<? } ?>
												</td>

												<td class="left">
													<? if ($user['unlock_orders']) { ?>
														<i class="fa fa-check-circle" style="color:#4ea24e"></i>
													<? } else { ?>
														<i class="fa fa-times-circle" style="color:#cf4a61"></i>
													<? } ?>
												</td>

												<td class="left">
													<? if ($user['do_transactions']) { ?>
														<i class="fa fa-check-circle" style="color:#4ea24e"></i>
													<? } else { ?>
														<i class="fa fa-times-circle" style="color:#cf4a61"></i>
													<? } ?>
												</td>

												<td class="left">
													<? if ($user['is_mainmanager']) { ?>
														<i class="fa fa-check-circle" style="color:#4ea24e"></i>
													<? } else { ?>
														<i class="fa fa-times-circle" style="color:#cf4a61"></i>
													<? } ?>
												</td>

												<td class="left">
													<? if ($user['is_headsales']) { ?>
														<i class="fa fa-check-circle" style="color:#4ea24e"></i>
													<? } else { ?>
														<i class="fa fa-times-circle" style="color:#cf4a61"></i>
													<? } ?>
												</td>

												<td class="left">
													<? if ($user['edit_csi']) { ?>
														<i class="fa fa-check-circle" style="color:#4ea24e"></i>
													<? } else { ?>
														<i class="fa fa-times-circle" style="color:#cf4a61"></i>
													<? } ?>
												</td>

												<td class="left">
													<? if ($user['own_orders']) { ?>
														<i class="fa fa-check-circle" style="color:#4ea24e"></i>
													<? } else { ?>
														<i class="fa fa-times-circle" style="color:#cf4a61"></i>
													<? } ?>
												</td>																						

												<td class="left">
													<? if ($user['ticket']) { ?>
														<i class="fa fa-check-circle" style="color:#4ea24e"></i>
													<? } else { ?>
														<i class="fa fa-times-circle" style="color:#cf4a61"></i>
													<? } ?>
												</td>

												<td class="left">
													<? if ($user['count_worktime']) { ?>
														<i class="fa fa-check-circle" style="color:#4ea24e"></i>
													<? } else { ?>
														<i class="fa fa-times-circle" style="color:#cf4a61"></i>
													<? } ?>
												</td>

												<td class="left">
													<? if ($user['count_content']) { ?>
														<i class="fa fa-check-circle" style="color:#4ea24e"></i>
													<? } else { ?>
														<i class="fa fa-times-circle" style="color:#cf4a61"></i>
													<? } ?>
												</td>

												<td class="left">
													<? if ($user['internal_pbx_num']) { ?>
														<span class="status_color" style="display:inline-block; padding:3px 5px; background:#ccc;"><?php echo $user['internal_pbx_num']; ?></span>
													<? } ?>
												</td>

												<td class="left">
													<? if ($user['bitrix_id']) { ?>
														<span class="status_color" style="display:inline-block; padding:3px 5px; background:#ccc;"><?php echo $user['bitrix_id']; ?></span>
													<? } ?>
												</td>

												<td class="left"><?php echo $user['date_added']; ?></td>
												<td class="right"><?php foreach ($user['action'] as $action) { ?>
													<a class="button" href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a>
													<?php } ?></td>
												</tr>
											<?php } ?>




										<? } else { ?>

											<tr>
												<td colspan="19" class="left"><b><? echo $name; ?></b></td>
											</td>
											<tr>
												<td width="1" style="text-align: center;"></td>
												<td class="left"><?php echo $column_username; ?></td>
												<td></td>
												<td class="center"><?php echo $column_status; ?></td>
												<td class="center">Супермен</td>
												<td class="center">Фронт-дев</td>
												<td class="center">Разблокировка</td>
												<td class="center">Транзакции</td>
												<td class="center">ГМ</td>
												<td class="center">РОП</td>
												<td class="center">CSI</td>
												<td class="center">Присв. заказы</td>
												<td class="center">Задачи</td>
												<td class="center">Время</td>
												<td class="center">Контент</td>
												<td class="center">SIP</td>
												<td class="center">B24</td>
												<td class="center"><?php echo $column_date_added; ?></td>
												<td class="right"><?php echo $column_action; ?></td>
											</tr>

											<?php foreach ($group as $user) { ?>
												<tr>
													<td style="text-align: center;"><?php if ($user['selected']) { ?>
														<input type="checkbox" name="selected[]" value="<?php echo $user['user_id']; ?>" checked="checked" />
													<?php } else { ?>
														<input type="checkbox" name="selected[]" value="<?php echo $user['user_id']; ?>" />
														<?php } ?></td>
														<td class="left">
															<?php echo $user['username']; ?>
														</td>
														<td class="left">
															<?php echo $user['firstname']; ?> <?php echo $user['lastname']; ?>
														</td>	

														<td class="center">
															<? if ($user['status']) { ?>
																<i class="fa fa-check-circle" style="color:#4ea24e"></i>
															<? } else { ?>
																<i class="fa fa-times-circle" style="color:#cf4a61"></i>
															<? } ?>
														</td>

														<td class="center">
															<? if ($user['is_av']) { ?>
																<i class="fa fa-check-circle" style="color:#4ea24e"></i>
															<? } else { ?>
																<i class="fa fa-times-circle" style="color:#cf4a61"></i>
															<? } ?>
														</td>

														<td class="center">
															<? if ($user['dev_template']) { ?>
																<i class="fa fa-check-circle" style="color:#4ea24e"></i>
															<? } else { ?>
																<i class="fa fa-times-circle" style="color:#cf4a61"></i>
															<? } ?>
														</td>

														<td class="center">
															<? if ($user['unlock_orders']) { ?>
																<i class="fa fa-check-circle" style="color:#4ea24e"></i>
															<? } else { ?>
																<i class="fa fa-times-circle" style="color:#cf4a61"></i>
															<? } ?>
														</td>

														<td class="center">
															<? if ($user['do_transactions']) { ?>
																<i class="fa fa-check-circle" style="color:#4ea24e"></i>
															<? } else { ?>
																<i class="fa fa-times-circle" style="color:#cf4a61"></i>
															<? } ?>
														</td>

														<td class="center">
															<? if ($user['is_mainmanager']) { ?>
																<i class="fa fa-check-circle" style="color:#4ea24e"></i>
															<? } else { ?>
																<i class="fa fa-times-circle" style="color:#cf4a61"></i>
															<? } ?>
														</td>

														<td class="center">
															<? if ($user['is_headsales']) { ?>
																<i class="fa fa-check-circle" style="color:#4ea24e"></i>
															<? } else { ?>
																<i class="fa fa-times-circle" style="color:#cf4a61"></i>
															<? } ?>
														</td>

														<td class="center">
															<? if ($user['edit_csi']) { ?>
																<i class="fa fa-check-circle" style="color:#4ea24e"></i>
															<? } else { ?>
																<i class="fa fa-times-circle" style="color:#cf4a61"></i>
															<? } ?>
														</td>

														<td class="center">
															<? if ($user['own_orders']) { ?>
																<i class="fa fa-check-circle" style="color:#4ea24e"></i>
															<? } else { ?>
																<i class="fa fa-times-circle" style="color:#cf4a61"></i>
															<? } ?>
														</td>																						

														<td class="center">
															<? if ($user['ticket']) { ?>
																<i class="fa fa-check-circle" style="color:#4ea24e"></i>
															<? } else { ?>
																<i class="fa fa-times-circle" style="color:#cf4a61"></i>
															<? } ?>
														</td>

														<td class="center">
															<? if ($user['count_worktime']) { ?>
																<i class="fa fa-check-circle" style="color:#4ea24e"></i>
															<? } else { ?>
																<i class="fa fa-times-circle" style="color:#cf4a61"></i>
															<? } ?>
														</td>

														<td class="left">
															<? if ($user['count_content']) { ?>
																<i class="fa fa-check-circle" style="color:#4ea24e"></i>
															<? } else { ?>
																<i class="fa fa-times-circle" style="color:#cf4a61"></i>
															<? } ?>
														</td>

														<td class="center">
															<? if ($user['internal_pbx_num']) { ?>
																<span class="status_color" style="display:inline-block; padding:3px 5px; background:#ccc;"><?php echo $user['internal_pbx_num']; ?></span>
															<? } ?>
														</td>

														<td class="center">
															<? if ($user['bitrix_id']) { ?>
																<span class="status_color" style="display:inline-block; padding:3px 5px; background:#ccc;"><?php echo $user['bitrix_id']; ?></span>
															<? } ?>
														</td>

														<td class="center"><?php echo $user['date_added']; ?></td>
														<td class="right"><?php foreach ($user['action'] as $action) { ?>
															<a class="button" href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a>
															<?php } ?></td>
														</tr>
													<?php } ?>

												<? } ?>
											<?php } ?>
										<?php } else { ?>
											<tr>
												<td class="center" colspan="5"><?php echo $text_no_results; ?></td>
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
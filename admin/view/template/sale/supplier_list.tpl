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
			<h1><img src="view/image/language.png" alt="" /> <?php echo $heading_title; ?></h1>
			<div class="buttons"><a href="<?php echo $insert; ?>" class="button"><?php echo $button_insert; ?></a><a onclick="$('form').submit();" class="button"><?php echo $button_delete; ?></a></div>
		</div>
		<div class="content">
			<form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
				<table class="list">
					<thead>
						<tr>
							<td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
							<td class="left" width="1">ID</td>
							<td class="left">Головной</td>
							<td class="left"><?php if ($sort == 'name') { ?>
								<a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
							<?php } else { ?>
								<a href="<?php echo $sort_name; ?>">Поставщик</a>
								<?php } ?></td>
								<td class="left">Страна</td>
								<td class="left">Рынок</td>
								<td class="left">Сроки в нал</td>
								<td class="left">Сроки не в нал</td>
								<td class="left">Хороший</td>
								<td class="left">Плохой</td>
								<td class="left">Коэффициент</td>
								<td class="right"><?php if ($sort == 'sort_order') { ?>
									<a href="<?php echo $sort_sort_order; ?>" class="<?php echo strtolower($order); ?>">Сортировка</a>
								<?php } else { ?>
									<a href="<?php echo $sort_sort_order; ?>">Сортировка</a>
									<?php } ?></td>
									<td class="right"><?php echo $column_action; ?></td>
								</tr>
							</thead>
							<tbody>
								<?php if ($suppliers) { ?>
									<?php foreach ($suppliers as $supplier) { ?>
										<tr>
											<td style="text-align: center;"><?php if ($supplier['selected']) { ?>
												<input type="checkbox" name="selected[]" value="<?php echo $supplier['supplier_id']; ?>" checked="checked" />
											<?php } else { ?>
												<input type="checkbox" name="selected[]" value="<?php echo $supplier['supplier_id']; ?>" />
												<?php } ?></td>
												<td class="left"><b><?php echo $supplier['supplier_id']; ?></b></td>        
												<td class="center">
													<? if (!$supplier['supplier_hassp']) { ?>
														<span class="status_color" style="background-color:orange;padding:4px 3px; color:#FFF;font-size:10px;"><?php echo $supplier['supplier_parent']; ?></span>
													<? } else { ?>
														<span class="status_color" style="padding:4px 3px; background-color:#2c82b8; color:#FFF;font-size:10px;"><?php echo $supplier['supplier_parent']; ?></span>
													<? } ?>
												</td>
												<td class="left">
													<span style="font-size:16px;"><b><?php echo $supplier['supplier_name']; ?></b></span><br /><span style="font-size:10px;"><?php echo $supplier['supplier_type']; ?></span>
												</td>
												<td class="left"><?php echo $supplier['supplier_country']; ?></td>
												<td class="center" style="padding:8px;">
													<? if ($supplier['supplier_inner']) { ?>
														<span class="status_color" style="background-color:orange;padding:4px 3px; color:#FFF;  font-size:10px;">
															Внутренний
														</span>
													<? } else { ?>
														<span class="status_color" style="background-color:#2c82b8;padding:4px 3px; color:#FFF; font-size:10px;">
															Внешний
														</span>
													<? } ?>
												</td>
												<td class="right">
													<?php if ($supplier['terms_instock']) { ?>
														<b><?php echo $supplier['terms_instock']; ?> дн.</b>
													<?php } ?>
												</td>
												<td class="right">
													<?php if ($supplier['terms_outstock']) { ?>
														<b><?php echo $supplier['terms_outstock']; ?> дн.</b>
													<?php } ?>
												</td>
												<td class="center" style="padding:8px;">
													<? if ($supplier['amzn_good']) { ?>
														<span class="status_color" style="display:inline-block; padding:3px 5px; background:#4ea24e; color:#FFF">Хороший</span>
													<? } else { ?>
														<span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF">Нейтральный</span>
													<? } ?>
												</td>

												<td class="center" style="padding:8px;">
													<? if ($supplier['amzn_bad']) { ?>
														<span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Плохой</span>
													<? } else { ?>
														<span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF">Нейтральный</span>
													<? } ?>
												</td>

												<td class="center" style="padding:8px;">
													<span class="status_color" style="display:inline-block; padding:3px 5px; background:black; color:#FFF">
														<?php if ($supplier['amzn_coefficient']) { ?><?php echo $supplier['amzn_coefficient'] ?><? } elseif ($supplier['amzn_good']) { ?>+10<?php } elseif ($supplier['amzn_bad']) { ?>-20<?php } else { ?>0<?php } ?>
													</span>
												</td>

												<td class="right"><?php echo $supplier['sort_order']; ?></td>
												<td class="right"><?php foreach ($supplier['action'] as $action) { ?>
													<a class="button" href="<?php echo $action['href']; ?>" <? if (isset($action['target'])) { ?>target="<? echo $action['target'] ?>"<? } ?>><?php echo $action['text']; ?></a>
													<?php } ?></td>
												</tr>


												<? if ($supplier['children']) { ?>
													<?php foreach ($supplier['children'] as $children) { ?>
														<tr>
															<td style="text-align: center;"><?php if ($children['selected']) { ?>
																<input type="checkbox" name="selected[]" value="<?php echo $supplier['supplier_id']; ?>" checked="checked" />
															<?php } else { ?>
																<input type="checkbox" name="selected[]" value="<?php echo $supplier['supplier_id']; ?>" />
																<?php } ?></td>
																<td class="left"><b><?php echo $children['supplier_id']; ?></b></td>
																<td class="center" style="padding:8px;">
																	<? if ($children['supplier_code']) { ?><span style="padding:4px 3px; background-color:#85B200; color:#FFF;"><?php echo $children['supplier_code']; ?></span>
																	<? } ?></td>         
																	<td class="right">
																		<? if (!$children['supplier_hassp']) { ?>
																			<span style="border-radius: 2px;padding: 10px 5px;background-color:orange; color:#FFF;"><?php echo $children['supplier_parent']; ?></span>
																		<? } else { ?>
																			<span style="border-radius: 2px;padding: 10px 5px;background-color:#2c82b8; color:#FFF;"><?php echo $children['supplier_parent']; ?></span>
																		<? } ?>
																	</td>
																	<td class="left"><span style="font-size:16px;"><?php echo $children['supplier_name']; ?></span><br /><span style="font-size:10px;"><?php echo $children['supplier_type']; ?></span></td>
																	<td class="left"><?php echo $children['supplier_country']; ?></td>
																	<td class="center" style="padding:8px;">
																		<? if ($children['supplier_inner']) { ?>
																			<span class="status_color_padding" style="background-color:orange; color:#FFF;">
																				Внутренний рынок
																			</span>
																		<? } else { ?>
																			<span class="status_color_padding" style="background-color:#2c82b8; color:#FFF;">
																				Внешний рынок
																			</span>
																			<? } ?></td>
																			<td class="right"><?php echo $children['sort_order']; ?></td>
																			<td class="right"><?php foreach ($children['action'] as $action) { ?>
																				[ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
																				<?php } ?></td>
																			</tr>
																		<?php } ?>


																	<? } ?>


																<?php } ?>




															<?php } else { ?>
																<tr>
																	<td class="center" colspan="6"><?php echo $text_no_results; ?></td>
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
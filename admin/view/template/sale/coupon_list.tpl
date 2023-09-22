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
			<h1><img src="view/image/customer.png" alt="" /> <?php echo $heading_title; ?></h1>
			<div class="buttons"><a href="<?php echo $insert; ?>" class="button"><?php echo $button_insert; ?></a><a onclick="location = '<?php echo $inserttracking; ?>'" class="button"><?php echo $button_insert_tracking; ?></a><a onclick="document.getElementById('form').submit();" class="button"><?php echo $button_delete; ?></a></div>
		</div>
		<div class="content">
			<form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
				<table class="list">
					<thead>
						<tr>
							<td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
							<td class="left"><?php if ($sort == 'cd.name') { ?>
								<a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
								<?php } else { ?>
								<a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
							<?php } ?></td>
							<td class="left"><?php if ($sort == 'c.code') { ?>
								<a href="<?php echo $sort_code; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_code; ?></a>
								<?php } else { ?>
								<a href="<?php echo $sort_code; ?>"><?php echo $column_code; ?></a>
							<?php } ?></td>
							<td><i class="fa fa-birthday-cake" aria-hidden="true" style="color:#7F00FF"></i></td>
							<td><i class="fa fa-clock-o" aria-hidden="true"></i></td>
							<td>Тип</td>						
							<td class="right"><?php if ($sort == 'c.discount') { ?>
								<a href="<?php echo $sort_discount; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_discount; ?></a>
								<?php } else { ?>
								<a href="<?php echo $sort_discount; ?>"><?php echo $column_discount; ?></a>
							<?php } ?></td>
							<td>В товарах</td>
							<td>Только нал.</td>
							<td>ПромоГруппа</td>
							<td>в ЛК</td>
							<td>Менеджер</td>							
							<td>Шаблон</td>
							<td>Акция</td>
							<? /* <td>Отправок</td> */ ?>
							<td>Использ выполн.</td>
							<td>Использ всех</td>
							<td class="left"><?php if ($sort == 'c.date_start') { ?>
								<a href="<?php echo $sort_date_start; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_start; ?></a>
								<?php } else { ?>
								<a href="<?php echo $sort_date_start; ?>"><?php echo $column_date_start; ?></a>
							<?php } ?></td>
							<td class="left"><?php if ($sort == 'c.date_end') { ?>
								<a href="<?php echo $sort_date_end; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_end; ?></a>
								<?php } else { ?>
								<a href="<?php echo $sort_date_end; ?>"><?php echo $column_date_end; ?></a>
							<?php } ?></td>
							<td class="left"><?php if ($sort == 'c.status') { ?>
								<a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
								<?php } else { ?>
								<a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
							<?php } ?></td>
							<td>Сегментация</td>
							<td class="right"><?php echo $column_action; ?></td>
						</tr>
					</thead>
					<tbody>
						<style>
							
						</style>
						<?php if ($coupons) { ?>
							<?php foreach ($coupons as $coupon) { ?>
								<tr>
									<td style="text-align: center;"><?php if ($coupon['selected']) { ?>
										<input type="checkbox" name="selected[]" value="<?php echo $coupon['coupon_id']; ?>" checked="checked" />
										<?php } else { ?>
										<input type="checkbox" name="selected[]" value="<?php echo $coupon['coupon_id']; ?>" />
									<?php } ?></td>
									<td class="left">
										<span class="status_color" style="display:inline-block; padding:3px 5px; background:#000; color:#FFF"><?php echo $coupon['name']; ?></span>
									</td>
									<td class="left">
										<span class="status_color" style="display:inline-block; padding:3px 5px; background:#000; color:#FFF"><?php echo $coupon['code']; ?></span>
									</td>
									<td class="center">
										<?php if ($coupon['birthday']) { ?>
											<i class="fa fa-birthday-cake" aria-hidden="true" style="font-size:24px;color:#7F00FF"></i>
										<? } ?>
									</td>
									<td class="left"><? if ($coupon['days_from_send']) { ?>+<?php echo $coupon['days_from_send']; ?> дн.<? } ?></td>
									<td class="left">
										<?php if ($coupon['type'] == 'P') { ?>
											<span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Процент</span>
											<? } elseif ($coupon['type'] == 'F') { ?>
											<span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Фиксированная</span>
											<? } elseif ($coupon['type'] == '3') { ?>
											<span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF">Третий в подарок</span>
											<? } elseif ($coupon['type'] == '4') { ?>
											<span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF">Четвертый в подарок</span>
											<? } elseif ($coupon['type'] == '5') { ?>
											<span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF">Пятый в подарок</span>
										<? } ?>
									</td>								
									<td class="right">
										<? if ($coupon['type'] == 'P') { ?>
											<?php echo (int)$coupon['discount']; ?> %
											<? } elseif ($coupon['type'] == 'F') { ?>
											<? if ($coupon['currency']) { ?>
												<?php echo (int)$coupon['discount']; ?> <? echo $coupon['currency']; ?>
												<? } else { ?>
												<?php echo (int)$coupon['discount']; ?> EUR
											<? } ?>
											<? } else { ?>
											нет
										<? } ?>
									</td>
									<td class="center">
									<? if ($coupon['display_list']) { ?>
										<i class="fa fa-check-circle" style="color:#4ea24e"></i>
									<? } else { ?>
										<i class="fa fa-times-circle" style="color:#cf4a61"></i>
									<? } ?>
									</td>
									
									<td class="center">
										<? if ($coupon['only_in_stock']) { ?>
										<i class="fa fa-check-circle" style="color:#4ea24e"></i>
									<? } else { ?>
										<i class="fa fa-times-circle" style="color:#cf4a61"></i>
									<? } ?>
									</td>
									
									<td class="center">
										<?php if ($coupon['promo_type']) { ?>
											<span class="status_color" style="display:inline-block; padding:3px 5px; background:#ccc;"><?php echo $coupon['promo_type']; ?></span>
										<? } ?>
									</td>
									
									<td class="center">
									<? if ($coupon['display_in_account']) { ?>
									<i class="fa fa-check-circle" style="color:#4ea24e"></i>
														<? } else { ?>
										<i class="fa fa-times-circle" style="color:#cf4a61"></i>
									<? } ?>
									</td>
									
									<td class="center">
										<?php if ($coupon['manager']) { ?><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ccc; font-size:10px;"><?php echo $coupon['manager']; ?></span><? } ?>
									</td>
									<td class="center">
										<? if ($coupon['actiontemplate']) { ?>
											<span class="status_color" style="display:inline-block; padding:3px 5px; background:#ccc; font-size:10px;"><?php echo $coupon['actiontemplate']; ?></span>
										<? } ?>
									</td>
									<td class="center">
										<? if ($coupon['linkedaction']) { ?>
											<span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; font-size:10px; color:#FFF"><?php echo $coupon['linkedaction']; ?></span>
										<? } ?>
									</td>
									<? /*	<td class="center">
										<? if ($coupon['actiontemplate_count']) { ?>
										<div class="status_color" style="display:block; padding:3px 5px; background:#ccc; font-size:14px;"><?php echo $coupon['actiontemplate_count']; ?></div>
										<div class="status_color" style="display:block; padding:3px 5px; background:#ccc; font-size:10px; margin-top:4px;">с <?php echo $coupon['actiontemplate_date_from']; ?></div>
										<? } ?>
										</td>
									*/ ?>
									<td class="center">
										<span class="status_color" style="display:inline-block; padding:3px 5px; background:#4ea24e; color:#FFF"><?php echo $coupon['usage_good']; ?></span>
									</td>
									<td class="center">
										<span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF"><?php echo $coupon['usage_bad']; ?></span>
									</td>
									
									<td class="left"><?php echo $coupon['date_start']; ?></td>
									<td class="left"><?php echo $coupon['date_end']; ?></td>
									<td class="left">
										<? if ($coupon['status']) { ?>
										<i class="fa fa-check-circle" style="color:#4ea24e"></i>
														<? } else { ?>
										<i class="fa fa-times-circle" style="color:#cf4a61"></i>
									<? } ?>
									</td>

									<td class="left">
										<? if ($coupon['show_in_segments']) { ?>
											<i class="fa fa-check-circle" style="color:#4ea24e"></i>
														<? } else { ?>
											<i class="fa fa-times-circle" style="color:#cf4a61"></i>
										<? } ?>
									</td>									
									<td class="right">
										<?php foreach ($coupon['action'] as $action) { ?>
											<a class="button" href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a>
										<?php } ?>
									</td>
								</tr>
							<?php } ?>
							<?php } else { ?>
							<tr>
								<td class="center" colspan="8"><?php echo $text_no_results; ?></td>
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
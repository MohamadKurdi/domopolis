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
			<h1><img src="view/image/information.png" alt="" /> <?php echo $heading_title; ?></h1>
			<div class="buttons"><a href="<?php echo $insert; ?>" class="button"><?php echo $button_insert; ?></a><a onclick="$('form').submit();" class="button"><?php echo $button_delete; ?></a></div>
		</div>
		<div class="content">
			<form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
				<table class="list">
					<thead>
						<tr>
							<td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
							<td class="left" width="60px" >
								Миниатюра
							</td>
							<td class="left">
								<?php if ($sort == 'id.title') { ?>
									<a href="<?php echo $sort_title; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_title; ?></a>
								<?php } else { ?>
									<a href="<?php echo $sort_title; ?>"><?php echo $column_title; ?></a>
								<?php } ?>
							</td>
							<td class="left">
								Шаблон
							</td>
							<td class="left">
								Функция подбора
							</td>
							<td class="left">
								Связанные промокоды
							</td>
							<td class="left">
								Текущий обзвон
							</td>
							<td class="left">
								Забытая корзина
							</td>
							<td class="left">
								Просмотров
							</td>
							<td class="right"><?php if ($sort == 'i.sort_order') { ?>
								<a href="<?php echo $sort_sort_order; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_sort_order; ?></a>
								<?php } else { ?>
								<a href="<?php echo $sort_sort_order; ?>"><?php echo $column_sort_order; ?></a>
							<?php } ?></td>
							<td class="right"><?php echo $column_action; ?></td>
						</tr>
					</thead>
					<tbody>
						<?php if ($actiontemplates) { ?>
							<?php foreach ($actiontemplates as $actiontemplate) { ?>
								<tr>
									<td style="text-align: center;">
										<?php if ($actiontemplate['selected']) { ?>
											<input type="checkbox" name="selected[]" value="<?php echo $actiontemplate['actiontemplate_id']; ?>" checked="checked" />
										<?php } else { ?>
											<input type="checkbox" name="selected[]" value="<?php echo $actiontemplate['actiontemplate_id']; ?>" />
										<?php } ?>
									</td>
									<td class="left">
										<img src="<?php echo $actiontemplate['image']; ?>" width="30px" height="30px" />
									</td>
									<td class="left">
										<b><?php echo $actiontemplate['title']; ?></b>
									</td>
									<td class="center">
										<? if ($actiontemplate['file_template']) { ?>
											<span style="font-family: 'courier new'; font-size:10px;"><?php echo $actiontemplate['file_template']; ?>
										<? } else { ?>
											<i class="fa fa-times-circle" style="color:#cf4a61"></i>
										<? } ?>		
									</td>
									<td class="center">
										<? if ($actiontemplate['data_function']) { ?>
											<span style="font-family: 'courier new'; font-size:10px;"><?php echo $actiontemplate['data_function']; ?>()
										<? } else { ?>
											<i class="fa fa-times-circle" style="color:#cf4a61"></i>
										<? } ?>		
									</td>
									<td class="left">
										<?php foreach ($actiontemplate['coupons'] as $coupon) { ?>
											<span class="status_color" style="display:inline-block; padding:3px 5px; background:#000; color:#FFF; margin-right:5px; margin-top: 5px;">
												<?php echo $coupon['code']; ?>												
											</span>
										<?php } ?>
									</td>
									<td class="center">
										<? if ($actiontemplate['use_for_manual']) { ?>
											<i class="fa fa-check-circle" style="color:#4ea24e"></i>
										<? } else { ?>
											<i class="fa fa-times-circle" style="color:#cf4a61"></i>
										<? } ?>											
									</td>
									<td class="center">
										<? if ($actiontemplate['use_for_forgotten']) { ?>
											<i class="fa fa-check-circle" style="color:#4ea24e"></i>
										<? } else { ?>
											<i class="fa fa-times-circle" style="color:#cf4a61"></i>
										<? } ?>											
									</td>
									<td class="center">
										<span class="status_color" style="display:inline-block; padding:3px 5px; background:#ccc;"><?php echo $actiontemplate['viewed']; ?></span>											
									</td>
									<td class="center">
										<span class="status_color" style="display:inline-block; padding:3px 5px; background:#000; color:#FFF"><?php echo $actiontemplate['sort_order']; ?></span>
									</td>
									<td class="right"><?php foreach ($actiontemplate['action'] as $action) { ?>
										<a class="button" href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a>
									<?php } ?></td>
								</tr>
							<?php } ?>
							<?php } else { ?>
							<tr>
								<td class="center" colspan="4"><?php echo $text_no_results; ?></td>
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
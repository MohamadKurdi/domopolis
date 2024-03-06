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
			<h1><img src="view/image/order.png" alt="" /> <?php echo $heading_title; ?></h1>
			<div class="buttons"><a href="<?php echo $insert; ?>" class="button"><?php echo $button_insert; ?></a><a onclick="$('form').submit();" class="button"><?php echo $button_delete; ?></a></div>
		</div>
		<div class="content">
			<form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
				<table class="list">
					<thead>
						<tr>
							<td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
							<td width="1" style="text-align: center;"></td>	
							<td class="left"><?php if ($sort == 'name') { ?>
								<a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
								<?php } else { ?>
								<a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
							<?php } ?></td>
							<td class="left">Цвет каталога</td>
							<td class="left">Связанные статусы</td>
							<td class="right"><?php echo $column_action; ?></td>
						</tr>
					</thead>
					<tbody>
						<?php if ($order_statuses) { ?>
							<?php foreach ($order_statuses as $order_status) { ?>
								<tr>
									<td><?php if ($order_status['selected']) { ?>
										<input type="checkbox" name="selected[]" value="<?php echo $order_status['order_status_id']; ?>" checked="checked" />
										<?php } else { ?>
										<input type="checkbox" name="selected[]" value="<?php echo $order_status['order_status_id']; ?>" />
									<?php } ?></td>
									<td width="1" style="text-align: center;"><b><?php echo $order_status['order_status_id']; ?></b></td>	
									<td class="left" style="width:300px;">
									<span class="status_color" style="text-align: left; background: #<?php echo !empty($order_status['status_bg_color']) ? $order_status['status_bg_color'] : ''; ?>; color: #<?php echo !empty($order_status['status_txt_color']) ? $order_status['status_txt_color'] : ''; ?>;">
									
									<? if ($order_status['status_fa_icon']) { ?><i class="fa <? echo $order_status['status_fa_icon']; ?>" aria-hidden="true"></i><? } ?>
										
									<?php echo $order_status['name']; ?></span>
									</td>
									<td class="left" style="width:300px;">
										<span class="status_color" style="text-align: left; background: #<?php echo !empty($order_status['front_bg_color']) ? $order_status['front_bg_color'] : $order_status['status_bg_color']; ?>; color: black;">Подсветка на фронте</span>
									</td>
									<td class="left">
										<?php foreach ($order_status['linked_order_statuses'] as $_los) { ?>
											<? echo $_los['name']; ?> 
										<? } ?>
									</td>
									<td class="right"><?php foreach ($order_status['action'] as $action) { ?>
										<a class="button" href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a>
									<?php } ?></td>
								</tr>
							<?php } ?>
							<?php } else { ?>
							<tr>
								<td class="center" colspan="3"><?php echo $text_no_results; ?></td>
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
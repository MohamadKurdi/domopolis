<?php echo $header; ?>
<div id="content">
	<div class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
		<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
		<?php } ?>
	</div>
	<?php if ($module_install) { ?>
	<?php if ($error_warning) { ?>
	<div class="warning"><?php echo $error_warning; ?></div>
	<?php } ?>
	<?php if ($success) { ?>
	<div class="success"><?php echo $success; ?></div>
	<?php } ?>
	<div class="box">
		<div class="heading order_head">
			<h1><img src="view/image/order.png" alt="" /> <?php echo $heading_title; ?></h1>
			<div class="buttons"><a href="<?php echo $send; ?>" class="button"><?php echo $button_subscribe; ?></a><a href="<?php echo $email; ?>" class="button"><?php echo $button_email; ?></a><a href="<?php echo $authorization; ?>" class="button"><?php echo $button_authorization; ?></a><a href="<?php echo $insert; ?>" class="button"><?php echo $button_insert; ?></a><a onclick="$('form').submit();" class="button"><?php echo $button_delete; ?></a></div>
		</div>
		<div class="content">
			<form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
				<table class="list">
					<thead>
						<tr>
							<td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
							<td class="left"><?php if ($sort == 'email') { ?>
								<a href="<?php echo $sort_email; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_email; ?></a>
								<?php } else { ?>
								<a href="<?php echo $sort_email; ?>"><?php echo $column_email; ?></a>
								<?php } ?></td>
							<td class="left"><?php if ($sort == 'status') { ?>
								<a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
								<?php } else { ?>
								<a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
								<?php } ?></td>
							<td class="right"><?php echo $column_action; ?></td>
						</tr>
					</thead>
					<tbody>
						<?php if ($subscribers) { ?>
						<?php foreach ($subscribers as $subscribe) { ?>
						<tr>
							<td style="text-align: center;"><?php if ($subscribe['selected']) { ?>
								<input type="checkbox" name="selected[]" value="<?php echo $subscribe['subscribe_id']; ?>" checked="checked" />
								<?php } else { ?>
								<input type="checkbox" name="selected[]" value="<?php echo $subscribe['subscribe_id']; ?>" />
								<?php } ?></td>
							<td class="left"><?php echo $subscribe['email']; ?></td>
							<td class="left"><?php echo $subscribe['status']; ?></td>
							<td class="right"><?php foreach ($subscribe['action'] as $action) { ?>
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
	<?php } else { ?>
	<div class="box">
		<div class="heading">
			<h1><img src="view/image/arbitrage.png" alt="" /> <?php echo $heading_title; ?></h1>
		</div>
		<div class="content">
			<div class="warning"><?php echo $text_module_not_exists; ?></div>
		</div>
	</div>
	<?php } ?>
</div>
<?php echo $footer; ?>

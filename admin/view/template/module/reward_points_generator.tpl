<?php echo $header; ?>
	<div id="content">
		<div class="breadcrumb">
			<?php foreach ($breadcrumbs as $breadcrumb) { ?>
				<?php echo $breadcrumb['separator']; ?>
				<a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
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
				<h1><img src="view/image/module.png" alt=""/> <?php echo $heading_title; ?></h1>

				<div class="buttons"><a onclick="$('#form').attr('action', '<?php echo $save; ?>'); $('#form').submit();" class="button"><?php echo $button_save; ?></a></a><a onclick="$('#form').submit();" class="button"><?php echo $button_generate; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
			</div>
			<div class="content">
				<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
					<table class="form">
						<tr>
							<td style="width: 230px;"><?php echo $entry_unit; ?></td>
							<td><select name="reward_points_generator[unit]">
									<?php foreach($units as $unit) { ?>
										<?php if (!empty($reward_points_generator['unit']) && $reward_points_generator['unit'] == $unit) { ?>
											<option value="<?php echo $unit; ?>" selected="selected"><?php echo $unit; ?></option>
										<?php } else { ?>
											<option value="<?php echo $unit; ?>"><?php echo $unit; ?></option>
										<?php } ?>
									<?php } ?>
							</select></td>
						</tr>
						<tr>
							<td>
								<?php echo $entry_price_points; ?>
								<span class="help"><?php echo $help_price_points; ?></span>
							</td>
							<td>
								<input type="text" name="reward_points_generator[points]" value="<?php echo !empty($reward_points_generator['points']) ? $reward_points_generator['points'] : '' ?>"/>
								<?php echo $this->config->get('config_currency'); ?> <?php echo $text_per; ?>
							</td>
						</tr>
						<tr>
							<td>
								<?php echo $entry_points_received; ?>
								<span class="help"><?php echo $help_points_received; ?></span>
							</td>
							<td>
								<table class="list" style="width:600px;margin-bottom:0;">
									<thead>
									<tr>
										<td class="left"><?php echo $entry_customer_group; ?></td>
										<td class="right"><?php echo $entry_reward; ?> <?php echo $this->config->get('config_currency'); ?></td>										
									</tr>
									</thead>
									<tbody>
									<?php foreach ($customer_groups as $customer_group) { ?>
										<tr>
											<td class="left"><?php echo $customer_group['name']; ?></td>
											<td class="right">
												<input type="text" name="reward_points_generator[product_reward][<?php echo $customer_group['customer_group_id']; ?>][points]" value="<?php echo !empty($reward_points_generator['product_reward'][$customer_group['customer_group_id']]['points']) ? $reward_points_generator['product_reward'][$customer_group['customer_group_id']]['points'] : '' ?>"/>
											</td>
										</tr>
									<?php } ?>
									</tbody>
								</table>
							</td>
						</tr>
						<tr>
							<td>
								<?php echo $entry_auto_add; ?>
								<span class="help"><?php echo $help_auto_add; ?></span>
							</td>
							<td>
								<select name="reward_points_generator[auto_order_id]">
									<?php array_unshift($order_statuses, array('order_status_id' => 0, 'name' => '')); ?>
									<?php foreach ($order_statuses as $order_status) { ?>
										<?php if ($order_status['order_status_id'] == $reward_points_generator['auto_order_id']) { ?>
											<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
										<?php } else { ?>
											<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
										<?php } ?>
									<?php } ?>
								</select>
							</td>
						</tr>
						<tr>
							<td>
								<?php echo $entry_auto_generate; ?>
								<span class="help"><?php echo $help_auto_generate; ?></span>
							</td>
							<td>
								<select name="reward_points_generator[auto_generate]">
									<?php if ($reward_points_generator['auto_generate']) { ?>
										<option value="1" selected="selected"><?php echo $text_yes; ?></option>
										<option value="0"><?php echo $text_no; ?></option>
										<?php } else { ?>
										<option value="1"><?php echo $text_yes; ?></option>
										<option value="0" selected="selected"><?php echo $text_no; ?></option>
									<?php } ?>
								</select>
							</td>
						</tr>	
						<tr>
							<td>
								<?php echo $entry_use_tax_class; ?>
								<span class="help"><?php echo $help_use_tax_class; ?></span>
							</td>
							<td>
								<select name="reward_points_generator[use_tax_class]">
									<?php if ($reward_points_generator['use_tax_class']) { ?>
										<option value="1" selected="selected"><?php echo $text_yes; ?></option>
										<option value="0"><?php echo $text_no; ?></option>
										<?php } else { ?>
										<option value="1"><?php echo $text_yes; ?></option>
										<option value="0" selected="selected"><?php echo $text_no; ?></option>
									<?php } ?>
								</select>
							</td>
						</tr>		
						<tr>
							<td>
								<?php echo $entry_generate_for_specials; ?>
								<span class="help"><?php echo $help_generate_for_specials; ?></span>
							</td>
							<td>
								<select name="reward_points_generator[product_reward][<?php echo $customer_group['customer_group_id']; ?>][no_generate_special]">
									<?php if ($reward_points_generator['product_reward'][$customer_group['customer_group_id']]['no_generate_special']) { ?>
										<option value="1" selected="selected"><?php echo $text_no; ?></option>
										<option value="0"><?php echo $text_yes; ?></option>
										<?php } else { ?>
										<option value="1"><?php echo $text_no; ?></option>
										<option value="0" selected="selected"><?php echo $text_yes; ?></option>
									<?php } ?>
								</select>
							</td>							
						</tr>
					</table>
				</form>
			</div>
		</div>
	</div>
<script type="text/javascript">
	$('select[name=\'reward_points_generator[unit]\']').change(function() {unit();});
	unit();

	function unit() {
		$('.unit').text($('select[name=\'reward_points_generator[unit]\']').val());
	}
</script>
<?php echo $footer; ?>
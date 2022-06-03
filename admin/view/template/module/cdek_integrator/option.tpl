<?php echo $header; ?>
<div id="content">
	<div class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
		<?php echo $breadcrumb['separator']; ?>
		<?php if ($breadcrumb['href']) { ?>
		<a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
		<?php } else { ?>
		<?php echo $breadcrumb['text']; ?>
		<?php } ?>
		<?php } ?>
	</div>
	<?php if ($error_warning) { ?>
	<div class="warning"><?php echo $error_warning; ?></div>
	<?php } ?>
	<?php if ($success) { ?>
	<div class="success"><?php echo $success; ?></div>
	<?php } ?>
	<div class="box">
	<div class="heading">
		<h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
		<div class="buttons">
			<a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a>
			<a onclick="$('#form').attr('action', $('#form').attr('action') + '&redirect=false'); $('#form').submit();" class="button"><?php echo $button_apply; ?></a>
			<a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a>
		</div>
	</div>
	<div class="content">
	 	<div id="vtabs" class="vtabs">
			<a href="#tab-data"><?php echo $tab_data; ?></a>
			<a href="#tab-auth"><?php echo $tab_auth; ?></a>
			<a href="#tab-order"><?php echo $tab_order; ?></a>
			<a href="#tab-status"><?php echo $tab_status; ?></a>
			<a href="#tab-currency"><?php echo $tab_currency; ?></a>
			<a href="#tab-additional"><?php echo $tab_additional; ?></a>
		</div>
		<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
			<div id="tab-data" class="vtabs-content">
				<table class="form">
					<tbody>
						<tr>
							<td><span class="required">*</span> <label for="setting-city"><?php echo $entry_city; ?></label></td>
							<td>
								<input type="hidden" class="setting-city-id" name="cdek_integrator_setting[city_id]" value="<?php if (!empty($setting['city_id'])) echo $setting['city_id']; ?>"/>
								<a class="js city-from<?php if (empty($setting['city_id'])) echo ' hidden'; ?>"><?php if (!empty($setting['city_name'])) echo $setting['city_name']; ?></a>
								<input type="text" name="cdek_integrator_setting[city_name]" value="<?php if (!empty($setting['city_name'])) echo $setting['city_name']; ?>" class="setting-city-name<?php if (!empty($setting['city_id'])) echo ' hidden'; ?>" />
								<?php if (isset($error['setting']['city_id'])) { ?>
								<span class="error"><?php echo $error['setting']['city_id']; ?></span>
								<?php } ?>
							</td>
						</tr>
						<tr>
							<td><label for="setting-city-default"><?php echo $entry_city_default; ?></label></td>
							<td>
								<select id="setting-city-default" name="cdek_integrator_setting[city_default]">	
									<?php foreach ($boolean_variables as $key => $value) { ?>
									<option <?php if (isset($setting['city_default']) && $key == $setting['city_default']) echo 'selected="selected"'; ?>value="<?php echo $key; ?>"><?php echo $value; ?></option>
									<?php } ?>
								</select>
							</td>
						</tr>
						<tr>
							<td><label for="setting-copy-count"><span class="required">*</span> <?php echo $entry_copy_count; ?></label></td>
							<td>
								<select id="setting-copy-count" name="cdek_integrator_setting[copy_count]">	
									<?php foreach (array(1,2,3,4) as $value) { ?>
									<option <?php if (isset($setting['copy_count']) && $setting['copy_count'] == $value) echo 'selected="selected"'; ?> value="<?php echo $value; ?>"><?php echo $value; ?></option>
									<?php } ?>
								</select>
							</td>
						</tr>
						<tr>
							<td><span class="required">*</span> <label for="setting-weight-class-id"><?php echo $entry_weight_class_id; ?></label></td>
							<td>
								<select id="setting-weight-class-id" name="cdek_integrator_setting[weight_class_id]">
									<?php foreach ($weight_classes as $weight_class) { ?>
									<option value="<?php echo $weight_class['weight_class_id']; ?>" <?php if (!empty($setting['weight_class_id']) && $setting['weight_class_id'] == $weight_class['weight_class_id']) echo 'selected="selected"'; ?>><?php echo $weight_class['title']; ?></option>
									<?php } ?>
								</select>
								<?php if (isset($error['setting']['weight_class_id'])) { ?>
								<span class="error"><?php echo $error['setting']['weight_class_id']; ?></span>
								<?php } ?>
							</td>
						</tr>
						<tr>
							<td><span class="required">*</span> <label for="setting-length-class-id"><?php echo $entry_length_class_id; ?></label></td>
							<td>
								<select id="setting-length-class-id" name="cdek_integrator_setting[length_class_id]">
									<?php foreach ($length_classes as $length_class) { ?>
									<option value="<?php echo $length_class['length_class_id']; ?>" <?php if (!empty($setting['length_class_id']) && $setting['length_class_id'] == $length_class['length_class_id']) echo 'selected="selected"'; ?>><?php echo $length_class['title']; ?></option>
									<?php } ?>
								</select>
								<?php if (isset($error['setting']['length_class_id'])) { ?>
								<span class="error"><?php echo $error['setting']['length_class_id']; ?></span>
								<?php } ?>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div id="tab-auth" class="vtabs-content">
				<table class="form">
					<tbody>
						<tr>
							<td><span class="required">*</span> <label for="setting-account"><?php echo $entry_account; ?></label></td>
							<td>
								<input id="setting-account" type="text" name="cdek_integrator_setting[account]" value="<?php if (!empty($setting['account'])) echo $setting['account']; ?>" />
								<?php if (isset($error['setting']['account'])) { ?>
								<span class="error"><?php echo $error['setting']['account']; ?></span>
								<?php } ?>
							</td>
						</tr>
						<tr>
							<td><span class="required">*</span> <label for="setting-secure-password"><?php echo $entry_secure_password; ?></label></td>
							<td>
								<input id="setting-secure-password" type="text" name="cdek_integrator_setting[secure_password]" value="<?php if (!empty($setting['secure_password'])) echo $setting['secure_password']; ?>" />
								<?php if (isset($error['setting']['secure_password'])) { ?>
								<span class="error"><?php echo $error['setting']['secure_password']; ?></span>
								<?php } ?>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div id="tab-currency" class="vtabs-content">
				<table class="form">
					<tbody>
						<tr>
							<td><label for="setting-currency"><?php echo $entry_currency; ?></label></td>
							<td>
								<select id="setting-currency" name="cdek_integrator_setting[currency]">	
									<?php foreach ($currency_list as $key => $value) { ?>
									<option <?php if (isset($setting['currency']) && $setting['currency'] == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $value; ?></option>
									<?php } ?>
								</select>
							</td>
						</tr>
						<tr>
							<td><label for="setting-currency-agreement"><?php echo $entry_currency_agreement; ?></label></td>
							<td>
								<select id="setting-currency-agreement" name="cdek_integrator_setting[currency_agreement]">	
									<?php foreach ($currency_list as $key => $value) { ?>
									<option <?php if (isset($setting['currency_agreement']) && $setting['currency_agreement'] == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $value; ?></option>
									<?php } ?>
								</select>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div id="tab-additional" class="vtabs-content">
				<div class="legend">Общие</div>
				<table class="form">
					<tbody>
						<tr class="parent">
							<td><label for="setting-replace-items"><?php echo $entry_replace_items; ?></label></td>
							<td>
								<select id="setting-replace-items" name="cdek_integrator_setting[replace_items]" class="slider">	
									<?php foreach ($boolean_variables as $key => $value) { ?>
									<option <?php if (isset($setting['replace_items']) && $setting['replace_items'] == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $value; ?></option>
									<?php } ?>
								</select>
								<span class="status"><?php echo (empty($package_info['replace_items']) || !$package_info['replace_items']) ? "&#9660;" : "&#9650;"; ?></span>
							</td>
						</tr>
						<tr>
							<td colspan="2" class="include">
								<div class="slider-content<?php if (!isset($setting['replace_items']) || !$setting['replace_items']) echo " hidden"; ?>" >
									<table class="form">
										<tbody>
											<tr>
												<td><label for="setting-replace-item-name"><span class="required">*</span> <?php echo $entry_replace_item_name; ?></label></td>
												<td>
													<input id="setting-replace-item-name" type="text" name="cdek_integrator_setting[replace_item_name]" value="<?php if (isset($setting['replace_item_name'])) echo $setting['replace_item_name']; ?>" maxlength="255" />
													<?php if (isset($error['setting']['replace_item_name'])) { ?>
													<span class="error"><?php echo $error['setting']['replace_item_name']; ?></span>
													<?php } ?>
												</td>
											</tr>
											<tr>
												<td><label for="setting-replace-item-cost"><?php echo $entry_replace_item_cost; ?></label></td>
												<td>
													<input id="setting-replace-item-cost" type="text" name="cdek_integrator_setting[replace_item_cost]" value="<?php if (isset($setting['replace_item_cost'])) echo $setting['replace_item_cost']; ?>" maxlength="255" />
													<?php if (isset($error['setting']['replace_item_cost'])) { ?>
													<span class="error"><?php echo $error['setting']['replace_item_cost']; ?></span>
													<?php } ?>
												</td>
											</tr>
											<tr>
												<td><label for="setting-replace-item-payment"><?php echo $entry_replace_item_payment; ?></label></td>
												<td>
													<input id="setting-replace-item-payment" type="text" name="cdek_integrator_setting[replace_item_payment]" value="<?php if (isset($setting['replace_item_payment'])) echo $setting['replace_item_payment']; ?>" maxlength="255" />
													<?php if (isset($error['setting']['replace_item_payment'])) { ?>
													<span class="error"><?php echo $error['setting']['replace_item_payment']; ?></span>
													<?php } ?>
												</td>
											</tr>
											<tr>
												<td><label for="setting-replace-item-amount"><?php echo $entry_replace_item_amount; ?></label></td>
												<td>
													<input id="setting-replace-item-amount" type="text" name="cdek_integrator_setting[replace_item_amount]" value="<?php if (!empty($setting['replace_item_amount'])) echo $setting['replace_item_amount']; ?>" maxlength="255" />
													<?php if (isset($error['setting']['replace_item_amount'])) { ?>
													<span class="error"><?php echo $error['setting']['replace_item_amount']; ?></span>
													<?php } ?>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</td>
						</tr>
						<tr>
							<td><label for="setting-cron"><?php echo $entry_use_cron; ?></label></td>
							<td>
								<select id="setting-cron" name="cdek_integrator_setting[use_cron]">	
									<?php foreach ($boolean_variables as $key => $value) { ?>
									<option <?php if (isset($setting['use_cron']) && $setting['use_cron'] == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $value; ?></option>
									<?php } ?>
								</select>
							</td>
						</tr>
						<tr>
							<td><label for="setting-cod"><?php echo $entry_cod_default; ?></label></td>
							<td>
								<select id="setting-cod" name="cdek_integrator_setting[cod]">	
									<?php foreach ($boolean_variables as $key => $value) { ?>
									<option <?php if (isset($setting['cod']) && $setting['cod'] == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $value; ?></option>
									<?php } ?>
								</select>
							</td>
						</tr>
						<tr>
							<td><label for="setting-delivery-recipient-cost"><?php echo $entry_delivery_recipient_cost; ?></label></td>
							<td>
								<input id="setting-delivery-recipient-cost" type="text" name="cdek_integrator_setting[delivery_recipient_cost]" value="<?php if (!empty($setting['delivery_recipient_cost'])) echo $setting['delivery_recipient_cost']; ?>" size="2" />
								<?php if (isset($error['setting']['delivery_recipient_cost'])) { ?>
								<span class="error"><?php echo $error['setting']['delivery_recipient_cost']; ?></span>
								<?php } ?>
							</td>
						</tr>
						<tr>
							<td><label for="setting-seller-name"><?php echo $entry_seller_name; ?></label></td>
							<td><input id="setting-seller-name" type="text" name="cdek_integrator_setting[seller_name]" value="<?php if (!empty($setting['seller_name'])) echo $setting['seller_name']; ?>" maxlength="255" /></td>
						</tr>
						<tr>
							<td><?php echo $entry_add_service; ?></td>
							<td>
								<div class="scrollbox cervices">
									<?php $class = 'even'; ?>
									<?php foreach ($add_cervices as $cervice_code => $cervice_info) { ?>
									<?php if (isset($cervice_info['hide'])) continue; ?>
									<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
									<div class="<?php echo $class; ?>">
										<input type="checkbox" name="cdek_integrator_setting[add_service][]" value="<?php echo $cervice_code; ?>" <?php if (!empty($setting['add_service']) && in_array($cervice_code, $setting['add_service'])) echo 'checked="checked"'; ?> />
										<div class="item-description">
											<?php echo $cervice_info['title']; ?><span class="help"><?php echo $cervice_info['description']; ?></span>
										</div>
									</div>
									<?php } ?>
								</div>
							</td>
						</tr>
					</tbody>
				</table>
				<div class="legend">Дополнительный вес</div>
				<table class="form">
					<tbody>
						<tr>
							<td><label for="setting-packing-min-weight"><?php echo $entry_packing_min_weight; ?></label></td>
							<td>
								<input id="setting-packing-min-weight" type="text" name="cdek_integrator_setting[packing_min_weight]" value="<?php if (!empty($setting['packing_min_weight'])) echo $setting['packing_min_weight']; ?>" size="1" />
								<select name="cdek_integrator_setting[packing_weight_class_id]">
									<?php foreach ($weight_classes as $weight_class) { ?>
									<option value="<?php echo $weight_class['weight_class_id']; ?>" <?php if (!empty($setting['packing_weight_class_id']) && $setting['packing_weight_class_id'] == $weight_class['weight_class_id']) echo 'selected="selected"'; ?>><?php echo $weight_class['title']; ?></option>
									<?php } ?>
								</select>
								<?php if (isset($error['setting']['packing_min_weight'])) { ?>
								<span class="error"><?php echo $error['setting']['packing_min_weight']; ?></span>
								<?php } ?>
							</td>
						</tr>
						<tr>
							<td><label for="setting-packing-value"><?php echo $entry_packing_additional_weight; ?></label></td>
							<td>
								<select name="cdek_integrator_setting[packing_prefix]">
									<?php foreach (array('+', '-') as $prefix) { ?>
									<option <?php if (!empty($setting['packing_prefix']) && $setting['packing_prefix'] == $prefix) echo 'selected="selected"'; ?> value="<?php echo $prefix; ?>"><?php echo $prefix; ?></option>
									<?php } ?>
								</select>
								<input id="setting-packing-value" type="text" name="cdek_integrator_setting[packing_value]" value="<?php if (!empty($setting['packing_value'])) echo $setting['packing_value']; ?>" size="1" />
								<select name="cdek_integrator_setting[packing_mode]">
									<?php foreach($additional_weight_mode as $key => $value) { ?>
									<option <?php if (!empty($setting['packing_mode']) && $setting['packing_mode'] == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $value; ?></option>
									<?php } ?>
								</select>
								<?php if (isset($error['setting']['packing_value'])) { ?>
								<span class="error"><?php echo $error['setting']['packing_value']; ?></span>
								<?php } ?>
							</td>
						</tr>
						
					</tbody>
				</table>
			</div>
			<div id="tab-status" class="vtabs-content">
				<p class="help"><?php echo $text_help_status_rule; ?></p>
				<table class="list">
					<thead>
						<tr>
							<td class="left"><?php echo $column_cdek_status; ?></td>
							<td class="left"><?php echo $column_new_status; ?></td>
							<td class="left"><?php echo $column_notify; ?></td>
							<td class="left"><?php echo $column_comment; ?></td>
							<td class="left"><?php echo $column_action; ?></td>
						</tr>
						<tbody>
							<?php if (!empty($setting['order_status_rule'])) { ?>
							<?php foreach ($setting['order_status_rule'] as $row => $rule_info) { ?>
							<tr rel="<?php echo $row; ?>">
								<td class="left">
									<select name="cdek_integrator_setting[order_status_rule][<?php echo $row; ?>][cdek_status_id]">
										<?php foreach ($cdek_statuses as $status_id => $cdek_status) { ?>
										<option <?php if ($rule_info['cdek_status_id'] ==  $status_id) echo 'selected="selected"'; ?> value="<?php echo $status_id; ?>"><?php echo $cdek_status['title']; ?></option>
										<?php } ?>
									</select>
								</td>
								<td class="left">
									<select name="cdek_integrator_setting[order_status_rule][<?php echo $row; ?>][order_status_id]">
										<?php foreach ($order_statuses as $order_status) { ?>
										<option <?php if ($rule_info['order_status_id'] ==  $order_status['order_status_id']) echo 'selected="selected"'; ?> value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
										<?php } ?>
									</select>
								</td>
								<td class="left">
									<select id="setting-city-default" name="cdek_integrator_setting[order_status_rule][<?php echo $row; ?>][notify]">
										<?php foreach ($boolean_variables as $key => $value) { ?>
										<option <?php if ($rule_info['notify'] == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $value; ?></option>
										<?php } ?>
									</select>
								</td>
								<td class="left">
									<p class="mt-0 link"><a class="js slider"><?php echo $text_tokens; ?></a></p>
									<div class="content" style="display:none">
										<table class="list token">
											<thead>
												<tr>
													<td width="30%" class="left"><?php echo $column_token; ?></td>
													<td width="70%" class="left"><?php echo $column_value; ?></td>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td class="left">{dispatch_number}</td>
													<td class="left"><?php echo $text_token_dispatch_number; ?></td>
												</tr>
												<tr>
													<td class="left">{order_id}</td>
													<td class="left"><?php echo $text_token_order_id; ?></td>
												</tr>
											</tbody>
										</table>
									</div>
									<textarea name="cdek_integrator_setting[order_status_rule][<?php echo $row; ?>][comment]" rows="3" cols="50"><?php echo $rule_info['comment']; ?></textarea>
								</td>
								<td class="left"><a class="button delete">Удалить</a></td>
							</tr>
							<?php } ?>
							<?php } ?>
						</tbody>
					</thead>
				</table>
				<a class="button" onclick="addStatusRule();">Добавить правило</a>
			</div>
			<div id="tab-order" class="vtabs-content">
				<table class="form">
					<tbody>
						<tr>
							<td><?php echo $entry_new_order_status_id; ?></td>
							<td>
								<div class="scrollbox">
									<?php $class = 'even'; ?>
									<?php foreach ($order_statuses as $order_status) { ?>
									<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
									<div class="<?php echo $class; ?>">
										<input type="checkbox" name="cdek_integrator_setting[new_order_status_id][]" value="<?php echo $order_status['order_status_id']; ?>" <?php  if (!empty($setting['new_order_status_id']) && in_array($order_status['order_status_id'], $setting['new_order_status_id'])) echo 'checked="checked"'; ?> />
										<?php echo $order_status['name']; ?>
									</div>
									<?php } ?>
								</div>
							</td>
						</tr>
						<tr>
							<td><label for="setting-new-order"><?php echo $entry_new_order; ?></label></td>
							<td>
								<input id="setting-new-order" type="text" name="cdek_integrator_setting[new_order]" value="<?php if (!empty($setting['new_order'])) echo $setting['new_order']; ?>" />
								<?php if (isset($error['setting']['new_order'])) { ?>
								<span class="error"><?php echo $error['setting']['new_order']; ?></span>
								<?php } ?>
							</td>
						</tr>
						<?php if ($show_filter) { ?>
						<tr>
							<td><?php echo $entry_shipping_methods; ?></td>
							<td>
								<div class="scrollbox">
									<?php $class = 'even'; ?>
									<?php foreach ($shipping_methods as $code => $name) { ?>
									<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
									<div class="<?php echo $class; ?>">
										<input type="checkbox" name="cdek_integrator_setting[shipping_method][]" value="<?php echo $code; ?>" <?php  if (!empty($setting['shipping_method']) && in_array($code, $setting['shipping_method'])) echo 'checked="checked"'; ?> />
										<?php echo $name; ?>
									</div>
									<?php } ?>
								</div>
							</td>
						</tr>
						<tr>
							<td><?php echo $entry_payment_methods; ?></td>
							<td>
								<div class="scrollbox">
									<?php $class = 'even'; ?>
									<?php foreach ($payment_methods as $code => $name) { ?>
									<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
									<div class="<?php echo $class; ?>">
										<input type="checkbox" name="cdek_integrator_setting[payment_method][]" value="<?php echo $code; ?>" <?php  if (!empty($setting['payment_method']) && in_array($code, $setting['payment_method'])) echo 'checked="checked"'; ?> />
										<?php echo $name; ?>
									</div>
									<?php } ?>
								</div>
							</td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</form>
	</div>
</div>
<script type="text/javascript"><!--

$('a.button.delete').live('click', function(){
	$(this).closest('tr').remove();
});

function addStatusRule() {
	
	var row = exists = -1;
	
	$('#tab-status table.list tr[rel]').each(function(){
		
		var rel = $(this).attr('rel');
		
		if (rel > row) {
			row = rel;
		}
		
	});
	
	row++;
	
	var html = '<tr rel="' + row + '">';
	html += '	<td class="left">';
	html += '		<select name="cdek_integrator_setting[order_status_rule][' + row + '][cdek_status_id]">';
	<?php foreach ($cdek_statuses as $status_id => $cdek_status) { ?>
	html += '			<option value="<?php echo $status_id; ?>"><?php echo $cdek_status['title']; ?></option>';
	<?php } ?>
	html += '		</select>';
	html += '	</td>';
	html += '	<td class="left">';
	html += '		<select name="cdek_integrator_setting[order_status_rule][' + row + '][order_status_id]">';
	<?php foreach ($order_statuses as $order_status) { ?>
	html += '			<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>';
	<?php } ?>
	html += '		</select>';
	html += '	</td>';
	html += '	<td class="left">';
	html += '		<select id="setting-city-default" name="cdek_integrator_setting[order_status_rule][' + row + '][notify]">';
	<?php foreach ($boolean_variables as $key => $value) { ?>
	html += '		<option value="<?php echo $key; ?>"><?php echo $value; ?></option>';
	<?php } ?>
	html += '		</select>';
	html += '	</td>';
	html += '	<td class="left">';
	html += '		<p class="mt-0 link">';
	html += '			<a class="js slider"><?php echo $text_tokens; ?></a>';
	html += '		</p>';
	html += '		<div class="content" style="display:none;">';
	html += '			<table class="list token">';
	html += '				<thead>';
	html += '					<tr>';
	html += '						<td width="30%" class="left"><?php echo $column_token; ?></td>';
	html += '						<td width="70%" class="left"><?php echo $column_value; ?></td>';
	html += '					</tr>';
	html += '				</thead>';
	html += '				<tbody>';
	html += '					<tr>';
	html += '						<td class="left">{dispatch_number}</td>';
	html += '						<td class="left"><?php echo $text_token_dispatch_number; ?></td>';
	html += '					</tr>';
	html += '					<tr>';
	html += '						<td class="left">{order_id}</td>';
	html += '						<td class="left"><?php echo $text_token_order_id; ?></td>';
	html += '					</tr>';
	html += '				</tbody>';
	html += '			</table>';
	html += '		</div>';
	html += '		<textarea name="cdek_integrator_setting[order_status_rule][' + row + '][comment]" rows="3" cols="50"></textarea>';
	html += '	</td>';
	html += '	<td class="left"><a class="button delete">Удалить</a></td>';
	html += '</tr>';
	
	$('#tab-status table.list tbody:first').append(html);
}

$('#vtabs a').tabs();
//--></script>
<?php echo $footer; ?>
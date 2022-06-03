<!doctype html> 
<!--
      //===========================================//
     // Advanced Banners                          //
    // Author: Joel Reeds                        //
   // Company: OpenCart Addons                  //
  // Website: http://opencartaddons.com        //
 // Contact: webmaster@opencartaddons.com     //
//===========================================//
-->
<?php echo $header; ?>	
<div id="content">
	<div class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
			<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
		<?php } ?>
	</div>	
	<?php if ($success) { ?>
        <div class="success"><?php echo $success; ?></div>
    <?php } ?>
	<?php if ($error_warning) { ?>
		<div class="warning"><?php echo $error_warning; ?></div>
	<?php } ?>
	<div class="box">
		<?php if ($ocversion < 150) { ?>
			<div class="left"></div>
			<div class="right"></div>
		<?php } ?>
		<div class="heading order_head">
			<?php if ($ocversion < 150) { ?>
				<h1 style="background-image: url('view/image/<?php echo $extensiontype; ?>.png');"><?php echo $heading_title; ?></h1>
			<?php } else { ?>
				<h1><img src="view/image/<?php echo $extensiontype; ?>.png" alt="" /> <?php echo $heading_title; ?></h1>
			<?php } ?>
			<div class="buttons"><a onclick="$('#form').attr('action', '<?php echo $action; ?>&apply=true'); $('#form').submit();" class="button"><span><?php echo $button_apply; ?></span></a><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
		</div>
		<div class="content">
			<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
								
				
				<input type="hidden" name="<?php echo $extension; ?>_name" value="<?php echo $extension; ?>">
	
				<div class="oca-options">
					<a onclick="expandAllModules();"><?php echo $button_expand; ?></a> / <a onclick="collapseAllModules();"><?php echo $button_collapse; ?></a>
				</div>
				
				<?php $module_row = 1; ?>
				<?php $banner_row = 1; ?>
				<?php if (!empty(${$extension . '_module'})) { ?>
					<?php foreach (${$extension . '_module'} as $data) { ?>
						<div id="module-row<?php echo $module_row; ?>" class="oca-rate">
							<div class="oca-head clearfix"><div class="oca-title"><?php echo substr($data['module_name'], 0, 100); if (strlen($data['module_name']) > 100) { echo '...'; } ?></div> <div class="oca-remove"><a onclick="copyModule(<?php echo $module_row; ?>);"><img src="view/image/oca_copy.png" alt="<?php echo $button_copy_module; ?>" title="<?php echo $button_copy_module; ?>" /></a>&nbsp;&nbsp;<a onclick="if (confirm('<?php echo $text_confirm_delete; ?>')) { $('#module-row<?php echo $module_row; ?>').remove(); }"><img src="view/image/oca_remove.png" alt="<?php echo $button_remove_module; ?>" title="<?php echo $button_remove_module; ?>" /></a></div></div>
							<div class="oca-content">
								<table class="oca-table">
									<thead>
										<tr>
											<td class="left" colspan="3" style="border-bottom: 1px solid #DDDDDD;"><?php echo $entry_description; ?> <input type="text" name="<?php echo $extension; ?>_module[<?php echo $module_row; ?>][module_name]" value="<?php echo $data['module_name']; ?>" size="115" /></td>
										</tr>
										<tr>
											<td class="center"><?php echo $text_general; ?></td>
											<td class="center"><?php echo $text_layout; ?></td>
											<td class="center"><?php echo $text_banner; ?></td>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td class="center" style="vertical-align:top;">
												<div class="oca-entry"><?php echo $entry_status; ?></div>
												<div class="oca-input">
													<select name="<?php echo $extension; ?>_module[<?php echo $module_row; ?>][status]">
														<?php if ($data['status']) { ?>
															<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
															<option value="0"><?php echo $text_disabled; ?></option>
														<?php } else { ?>
															<option value="1"><?php echo $text_enabled; ?></option>
															<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
														<?php } ?>
													</select>
												</div>
												<div class="oca-entry"><?php echo $entry_cache; ?></div>
												<div class="oca-input">
													<select name="<?php echo $extension; ?>_module[<?php echo $module_row; ?>][cache]">
														<?php if ($data['cache']) { ?>
															<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
															<option value="0"><?php echo $text_disabled; ?></option>
														<?php } else { ?>
															<option value="1"><?php echo $text_enabled; ?></option>
															<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
														<?php } ?>
													</select>
												</div>
												<div class="oca-entry"><?php echo $entry_sort_order; ?></div>
												<div class="oca-input">
													<input type="text" name="<?php echo $extension; ?>_module[<?php echo $module_row; ?>][sort_order]" value="<?php echo $data['sort_order']; ?>" size="5" />
												</div>
												<div class="oca-entry"><?php echo $entry_stores; ?></div>
												<div class="oca-input">
													<div class="scrollbox">
														<?php $class = 'even'; ?>
														<div class="<?php echo $class; ?>">
															<?php if (!empty($data['stores']) && in_array(0, $data['stores'])) { ?>
																<input type="checkbox" name="<?php echo $extension; ?>_module[<?php echo $module_row; ?>][stores][]" value="0" checked="checked" />
																<?php echo $this->config->get('config_name'); ?>
															<?php } else { ?>
																<input type="checkbox" name="<?php echo $extension; ?>_module[<?php echo $module_row; ?>][stores][]" value="0" />
																<?php echo $this->config->get('config_name'); ?>
															<?php } ?>
														</div>
														<?php foreach ($stores as $store) { ?>
															<?php $class = ($class == 'even') ? 'odd' : 'even'; ?>
															<div class="<?php echo $class; ?>">
																<?php if (!empty($data['stores']) && in_array($store['store_id'], $data['stores'])) { ?>
																	<input type="checkbox" name="<?php echo $extension; ?>_module[<?php echo $module_row; ?>][stores][]" value="<?php echo $store['store_id']; ?>" checked="checked" />
																	<?php echo $store['name']; ?>
																<?php } else { ?>
																	<input type="checkbox" name="<?php echo $extension; ?>_module[<?php echo $module_row; ?>][stores][]" value="<?php echo $store['store_id']; ?>" />
																	<?php echo $store['name']; ?>
																<?php } ?>
															</div>
														<?php } ?>
													</div>
													<a onclick="$(this).parent().find(':checkbox').attr('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false);"><?php echo $text_unselect_all; ?></a>
												</div>															
												<div class="oca-entry"><?php echo $entry_customer_groups; ?></div>
												<div class="oca-input">
													<div class="scrollbox">
														<?php $class = 'even'; ?>
														<div class="<?php echo $class; ?>">
															<?php if (!empty($data['customer_groups']) && in_array(0, $data['customer_groups'])) { ?>
																<input type="checkbox" name="<?php echo $extension; ?>_module[<?php echo $module_row; ?>][customer_groups][]" value="0" checked="checked" />
																<i><?php echo $text_guest_checkout; ?></i>
															<?php } else { ?>
																<input type="checkbox" name="<?php echo $extension; ?>_module[<?php echo $module_row; ?>][customer_groups][]" value="0" />
																<i><?php echo $text_guest_checkout; ?></i>
															<?php } ?>
														</div>
														<?php foreach ($customer_groups as $customer_group) { ?>
															<?php $class = ($class == 'even') ? 'odd' : 'even'; ?>
															<div class="<?php echo $class; ?>">
															<?php if (!empty($data['customer_groups']) && in_array($customer_group['customer_group_id'], $data['customer_groups'])) { ?>
																<input type="checkbox" name="<?php echo $extension; ?>_module[<?php echo $module_row; ?>][customer_groups][]" value="<?php echo $customer_group['customer_group_id']; ?>" checked="checked" />
																<?php echo $customer_group['name']; ?>
															<?php } else { ?>
																<input type="checkbox" name="<?php echo $extension; ?>_module[<?php echo $module_row; ?>][customer_groups][]" value="<?php echo $customer_group['customer_group_id']; ?>" />
																<?php echo $customer_group['name']; ?>
																<?php } ?>
															</div>
														<?php } ?>
													</div>
													<a onclick="$(this).parent().find(':checkbox').attr('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false);"><?php echo $text_unselect_all; ?></a>
												</div>
												<div class="oca-entry"><?php echo $entry_dates; ?></div>
												<div class="oca-input" align="center">
													<table class="oca-table-noborder" style="width:150px;">
														<thead>
															<tr>
																<td class="center"><?php echo $text_start; ?></td>
																<td class="center"><?php echo $text_end; ?></td>
															</tr>
														</thead>
														<tbody>
															<tr>
																<td class="center"><input type="text" name="<?php echo $extension; ?>_module[<?php echo $module_row; ?>][date_start]" value="<?php echo $data['date_start']; ?>" class="date" size="8" /></td>
																<td class="center"><input type="text" name="<?php echo $extension; ?>_module[<?php echo $module_row; ?>][date_end]" value="<?php echo $data['date_end']; ?>" class="date"  size="8" /></td>
															</tr>
														</tbody>
													</table>
												</div>
											</td>
											<td class="center" style="vertical-align:top;">
												<div class="oca-entry"><?php echo $entry_categories; ?></div>
												<div class="oca-input">
													<div class="scrollbox" style="height:150px;">
														<?php $class = 'even'; ?>
														<div class="<?php echo $class; ?>">
															<?php if (!empty($data['categories']) && in_array(0, $data['categories'])) { ?>
																<input type="checkbox" name="<?php echo $extension; ?>_module[<?php echo $module_row; ?>][categories][]" value="0" checked="checked" />
																<i><?php echo $text_all_categories; ?></i>
															<?php } else { ?>
																<input type="checkbox" name="<?php echo $extension; ?>_module[<?php echo $module_row; ?>][categories][]" value="0" />
																<i><?php echo $text_all_categories; ?></i>
															<?php } ?>
														</div>
														<?php foreach ($categories as $category) { ?>
															<?php $class = ($class == 'even') ? 'odd' : 'even'; ?>
															<div class="<?php echo $class; ?>">
															<?php if (!empty($data['categories']) && in_array($category['category_id'], $data['categories'])) { ?>
																<input type="checkbox" name="<?php echo $extension; ?>_module[<?php echo $module_row; ?>][categories][]" value="<?php echo $category['category_id']; ?>" checked="checked" />
																<?php echo $category['name']; ?>
															<?php } else { ?>
																<input type="checkbox" name="<?php echo $extension; ?>_module[<?php echo $module_row; ?>][categories][]" value="<?php echo $category['category_id']; ?>" />
																<?php echo $category['name']; ?>
																<?php } ?>
															</div>
														<?php } ?>
													</div>
													<a onclick="$(this).parent().find(':checkbox').attr('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false);"><?php echo $text_unselect_all; ?></a>
												</div>
												<div class="oca-entry">Бренды</div>
												<div class="oca-input">
													<div class="scrollbox" style="height:150px;">
														<?php $class = 'even'; ?>
														<div class="<?php echo $class; ?>">
															<?php if (!empty($data['manufacturers']) && in_array(0, $data['manufacturers'])) { ?>
																<input type="checkbox" name="<?php echo $extension; ?>_module[<?php echo $module_row; ?>][manufacturers][]" value="0" checked="checked" />
																<i>Все Бренды</i>
															<?php } else { ?>
																<input type="checkbox" name="<?php echo $extension; ?>_module[<?php echo $module_row; ?>][manufacturers][]" value="0" />
																<i>Все Бренды</i>
															<?php } ?>
														</div>
														<?php foreach ($manufacturers as $manufacturer) { ?>
															<?php $class = ($class == 'even') ? 'odd' : 'even'; ?>
															<div class="<?php echo $class; ?>">
															<?php if (!empty($data['manufacturers']) && in_array($manufacturer['manufacturer_id'], $data['manufacturers'])) { ?>
																<input type="checkbox" name="<?php echo $extension; ?>_module[<?php echo $module_row; ?>][manufacturers][]" value="<?php echo $manufacturer['manufacturer_id']; ?>" checked="checked" />
																<?php echo $manufacturer['name']; ?>
															<?php } else { ?>
																<input type="checkbox" name="<?php echo $extension; ?>_module[<?php echo $module_row; ?>][manufacturers][]" value="<?php echo $manufacturer['manufacturer_id']; ?>" />
																<?php echo $manufacturer['name']; ?>
																<?php } ?>
															</div>
														<?php } ?>
													</div>
													<a onclick="$(this).parent().find(':checkbox').attr('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false);"><?php echo $text_unselect_all; ?></a>
												</div>
												<div class="oca-entry"><?php echo $entry_languages; ?></div>
												<div class="oca-input">
													<div class="scrollbox">
														<?php $class = 'even'; ?>
														<div class="<?php echo $class; ?>">
															<?php if (!empty($data['languages']) && in_array('0', $data['languages'])) { ?>
																<input type="checkbox" name="<?php echo $extension; ?>_module[<?php echo $module_row; ?>][languages][]" value="0" checked="checked" />
																<?php echo $text_all_languages; ?>
															<?php } else { ?>
																<input type="checkbox" name="<?php echo $extension; ?>_module[<?php echo $module_row; ?>][languages][]" value="0" />
																<?php echo $text_all_languages; ?>
															<?php } ?>
														</div>
														<?php foreach ($languages as $language) { ?>
															<?php $class = ($class == 'even') ? 'odd' : 'even'; ?>
															<div class="<?php echo $class; ?>">
																<?php if (!empty($data['languages']) && in_array($language['code'], $data['languages'])) { ?>
																	<input type="checkbox" name="<?php echo $extension; ?>_module[<?php echo $module_row; ?>][languages][]" value="<?php echo $language['code']; ?>" checked="checked" />
																	<?php echo $language['name']; ?>
																<?php } else { ?>
																	<input type="checkbox" name="<?php echo $extension; ?>_module[<?php echo $module_row; ?>][languages][]" value="<?php echo $language['code']; ?>" />
																	<?php echo $language['name']; ?>
																<?php } ?>
															</div>
														<?php } ?>
													</div>
													<a onclick="$(this).parent().find(':checkbox').attr('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false);"><?php echo $text_unselect_all; ?></a>
												</div>
												<div class="oca-entry"><?php echo $entry_layout; ?></div>
												<div class="oca-input" align="center">
													<select name="<?php echo $extension; ?>_module[<?php echo $module_row; ?>][layout_id]">
														<?php foreach ($layouts as $layout) { ?>
															<?php if ($layout['layout_id'] == $data['layout_id']) { ?>
																<option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
															<?php } else { ?>
																<option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
															<?php } ?>
														<?php } ?>
													</select>
												</div>
												<div class="oca-entry"><?php echo $entry_position; ?></div>
												<div class="oca-input">
													<select name="<?php echo $extension; ?>_module[<?php echo $module_row; ?>][position]">
														<?php foreach ($positions as $position) { ?>
															<?php if ($position['id'] == $data['position']) { ?>
																<option value="<?php echo $position['id']; ?>" selected="selected"><?php echo $position['name']; ?></option>
															<?php } else { ?>
																<option value="<?php echo $position['id']; ?>"><?php echo $position['name']; ?></option>
															<?php } ?>
														<?php } ?>
													</select>
												</div>
											</td>
											<td class="center" style="vertical-align:top;">	
												<div class="oca-entry"><?php echo $entry_banners; ?></div>
												<div class="oca-input">
													<table class="oca-table-noborder sortable" style="width: 100%">
														<thead>
															<tr>
																<td class="center">&nbsp;</td>
																<td class="center"><?php echo $entry_banner; ?></td>
																<td class="center" colspan="2"><?php echo $entry_dimension; ?></td>
																<td class="center" colspan="4"><?php echo $entry_margin; ?></td>
																<td class="center"><?php echo $entry_float; ?></td>
																<td class="center"><?php echo $entry_clear; ?></td>
																<td class="center">&nbsp;</td>
															</tr>
															<tr style="border-bottom: 1px solid #DDDDDD;">
																<td class="center">&nbsp;</td>
																<td class="center">&nbsp;</td>
																<td class="center"><?php echo $text_width; ?></td>
																<td class="center"><?php echo $text_height; ?></td>
																<td class="center"><?php echo $text_left; ?></td>
																<td class="center"><?php echo $text_right; ?></td>
																<td class="center"><?php echo $text_top; ?></td>
																<td class="center"><?php echo $text_bottom; ?></td>
																<td class="center">&nbsp;</td>
																<td class="center">&nbsp;</td>
																<td class="center">&nbsp;</td>	
															</tr>
														</thead>
														<tbody>
															<?php $module_banners = isset($data['banners']) ? $data['banners'] : false; ?>
															<?php if ($module_banners) { ?>
																<?php foreach ($module_banners as $module_banner) { ?>
																	<tr id="banner-row<?php echo $banner_row; ?>" style="border-bottom: 1px solid #DDDDDD;">
																		<td class="center" style="vertical-align: middle;"><div class="oca-sort"><img src="view/image/oca_sort.gif" alt="<?php echo $button_move_banner; ?>" /></div></td>
																		<td class="center">
																			<select name="<?php echo $extension; ?>_module[<?php echo $module_row; ?>][banners][<?php echo $banner_row; ?>][banner_id]">
																				<?php foreach ($banners as $banner) { ?>
																					<?php if ($banner['banner_id'] == $module_banner['banner_id']) { ?>
																						<option value="<?php echo $banner['banner_id']; ?>" selected="selected"><?php echo $banner['name']; ?></option>
																					<?php } else { ?>
																						<option value="<?php echo $banner['banner_id']; ?>"><?php echo $banner['name']; ?></option>
																					<?php } ?>
																				<?php } ?>
																			</select>
																		</td>
																		<td class="center"><input type="text" name="<?php echo $extension; ?>_module[<?php echo $module_row; ?>][banners][<?php echo $banner_row; ?>][image_width]" value="<?php echo $module_banner['image_width']; ?>" size="5" /></td>
																		<td class="center"><input type="text" name="<?php echo $extension; ?>_module[<?php echo $module_row; ?>][banners][<?php echo $banner_row; ?>][image_height]" value="<?php echo $module_banner['image_height']; ?>" size="5" /></td>
																		<td class="center"><input type="text" name="<?php echo $extension; ?>_module[<?php echo $module_row; ?>][banners][<?php echo $banner_row; ?>][margin_left]" value="<?php echo $module_banner['margin_left']; ?>" size="5" /></td>
																		<td class="center"><input type="text" name="<?php echo $extension; ?>_module[<?php echo $module_row; ?>][banners][<?php echo $banner_row; ?>][margin_right]" value="<?php echo $module_banner['margin_right']; ?>" size="5" /></td>
																		<td class="center"><input type="text" name="<?php echo $extension; ?>_module[<?php echo $module_row; ?>][banners][<?php echo $banner_row; ?>][margin_top]" value="<?php echo $module_banner['margin_top']; ?>" size="5" /></td>
																		<td class="center"><input type="text" name="<?php echo $extension; ?>_module[<?php echo $module_row; ?>][banners][<?php echo $banner_row; ?>][margin_bottom]" value="<?php echo $module_banner['margin_bottom']; ?>" size="5" /></td>
																		<td class="center">
																			<select name="<?php echo $extension; ?>_module[<?php echo $module_row; ?>][banners][<?php echo $banner_row; ?>][float]">
																				<?php foreach ($floats as $float) { ?>
																					<?php if ($float == $module_banner['float']) { ?>
																						<option value="<?php echo $float; ?>" selected="selected"><?php echo $float; ?></option>
																					<?php } else { ?>
																						<option value="<?php echo $float; ?>"><?php echo $float; ?></option>
																					<?php } ?>
																				<?php } ?>
																			</select>
																		</td>
																		<td class="center">
																			<select name="<?php echo $extension; ?>_module[<?php echo $module_row; ?>][banners][<?php echo $banner_row; ?>][clear]">
																				<?php foreach ($clears as $clear) { ?>
																					<?php if ($clear == $module_banner['clear']) { ?>
																						<option value="<?php echo $clear; ?>" selected="selected"><?php echo $clear; ?></option>
																					<?php } else { ?>
																						<option value="<?php echo $clear; ?>"><?php echo $clear; ?></option>
																					<?php } ?>
																				<?php } ?>
																			</select>
																		</td>
																		<td class="right" style="vertical-align: middle;"><a onclick="$('#banner-row<?php echo $banner_row; ?>').remove();"><img src="view/image/delete.png" alt="<?php echo $button_remove_banner; ?>" /></a></td>
																	</tr>
																	<?php $banner_row++; ?>
																<?php } ?>
															<?php } ?>
															<tr id="module-row<?php echo $module_row; ?>-banner"><td class="right" style="line-height: 32px;" colspan="11" style="vertical-align: middle;"><a onclick="addBanner(<?php echo $module_row; ?>)"><img src="view/image/add.png" alt="<?php echo $button_add_banner; ?>" /></a></td></tr>
														</tbody>
													</table>
												</div>
												<div class="oca-entry"><?php echo $entry_display; ?></div>
												<div class="oca-input">
													<select name="<?php echo $extension; ?>_module[<?php echo $module_row; ?>][display_type]" onChange="toggleDisplayType(<?php echo $module_row; ?>, $(this).val())">
														<?php foreach ($display_types as $display_type) { ?>
															<?php if ($display_type['id'] == $data['display_type']) { ?>
																<option value="<?php echo $display_type['id']; ?>" selected="selected"><?php echo $display_type['name']; ?></option>
															<?php } else { ?>
																<option value="<?php echo $display_type['id']; ?>"><?php echo $display_type['name']; ?></option>
															<?php } ?>
														<?php } ?>
													</select>
												</div>
												<div id="banner<?php echo $module_row; ?>" <?php if ($data['display_type'] != 0) { ?>style="display: none;"<?php } ?>>
													<div class="oca-entry"><?php echo $entry_banner_settings; ?></div>
													<div class="oca-input">
														<table class="oca-table-noborder">
															<thead>
																<tr>
																	<td class="left"><?php echo $text_option; ?></td>
																	<td class="left"><?php echo $text_value; ?></td>
																</tr>
															</thead>
															<tbody>
																<tr>
																	<td class="left"><?php echo $entry_banner_effect; ?></td>
																	<td class="left">
																		<select name="<?php echo $extension; ?>_module[<?php echo $module_row; ?>][banner_effect]">
																			<?php foreach ($banner_effects as $banner_effect) { ?>
																				<?php if ($banner_effect == $data['banner_effect']) { ?>
																					<option value="<?php echo $banner_effect; ?>" selected="selected"><?php echo $banner_effect; ?></option>
																				<?php } else { ?>
																					<option value="<?php echo $banner_effect; ?>"><?php echo $banner_effect; ?></option>
																				<?php } ?>
																			<?php } ?>
																		</select>
																	</td>
																</tr>
																<tr>
																	<td class="left"><?php echo $entry_banner_speed; ?></td>
																	<td class="left">
																		<input type="text" name="<?php echo $extension; ?>_module[<?php echo $module_row; ?>][banner_speed]" value="<?php echo $data['banner_speed']; ?>" size="5" />
																	</td>
																</tr>
																<tr>
																	<td class="left"><?php echo $entry_banner_timeout; ?></td>
																	<td class="left">
																		<input type="text" name="<?php echo $extension; ?>_module[<?php echo $module_row; ?>][banner_timeout]" value="<?php echo $data['banner_timeout']; ?>" size="5" />
																	</td>
																</tr>
																<tr>
																	<td class="left"><?php echo $entry_banner_pause; ?></td>
																	<td class="left">
																		<select name="<?php echo $extension; ?>_module[<?php echo $module_row; ?>][banner_pause]">
																			<?php if ($data['banner_pause'] == 1) { ?>
																				<option value="1" selected="selected"><?php echo $text_true; ?></option>
																				<option value="0"><?php echo $text_false; ?></option>
																			<?php } else { ?>
																				<option value="1"><?php echo $text_true; ?></option>
																				<option value="0" selected="selected"><?php echo $text_false; ?></option>
																			<?php } ?>
																		</select>
																	</td>
																</tr>
															</tbody>
														</table>
													</div>
												</div>
												<div id="slideshow<?php echo $module_row; ?>" <?php if ($data['display_type'] != 1) { ?>style="display: none;"<?php } ?>>
													<div class="oca-entry"><?php echo $entry_slideshow_settings; ?></div>
													<div class="oca-input">
														<table class="oca-table-noborder">
															<thead>
																<tr>
																	<td class="left"><?php echo $text_option; ?></td>
																	<td class="left"><?php echo $text_value; ?></td>
																</tr>
															</thead>
															<tbody>
																<tr>
																	<td class="left"><?php echo $entry_slideshow_effect; ?></td>
																	<td class="left">
																		<select name="<?php echo $extension; ?>_module[<?php echo $module_row; ?>][slideshow_effect]">
																			<?php foreach ($slideshow_effects as $slideshow_effect) { ?>
																				<?php if ($slideshow_effect == $data['slideshow_effect']) { ?>
																					<option value="<?php echo $slideshow_effect; ?>" selected="selected"><?php echo $slideshow_effect; ?></option>
																				<?php } else { ?>
																					<option value="<?php echo $slideshow_effect; ?>"><?php echo $slideshow_effect; ?></option>
																				<?php } ?>
																			<?php } ?>
																		</select>
																	</td>
																</tr>
																<tr>
																	<td class="left"><?php echo $entry_slideshow_animspeed; ?></td>
																	<td class="left">
																		<input type="text" name="<?php echo $extension; ?>_module[<?php echo $module_row; ?>][slideshow_animspeed]" value="<?php echo $data['slideshow_animspeed']; ?>" size="5" />
																	</td>
																</tr>
																<tr>
																	<td class="left"><?php echo $entry_slideshow_pausetime; ?></td>
																	<td class="left">
																		<input type="text" name="<?php echo $extension; ?>_module[<?php echo $module_row; ?>][slideshow_pausetime]" value="<?php echo $data['slideshow_pausetime']; ?>" size="5" />
																	</td>
																</tr>
																<tr>
																	<td class="left"><?php echo $entry_slideshow_startslide; ?></td>
																	<td class="left">
																		<input type="text" name="<?php echo $extension; ?>_module[<?php echo $module_row; ?>][slideshow_startslide]" value="<?php echo $data['slideshow_startslide']; ?>" size="5" />
																	</td>
																</tr>
																<tr>
																	<td class="left"><?php echo $entry_slideshow_directionnav; ?></td>
																	<td class="left">
																		<select name="<?php echo $extension; ?>_module[<?php echo $module_row; ?>][slideshow_directionnav]">
																			<?php if ($data['slideshow_directionnav'] == 'true') { ?>
																				<option value="true" selected="selected"><?php echo $text_true; ?></option>
																				<option value="false"><?php echo $text_false; ?></option>
																			<?php } else { ?>
																				<option value="true"><?php echo $text_true; ?></option>
																				<option value="false" selected="selected"><?php echo $text_false; ?></option>
																			<?php } ?>
																		</select>
																	</td>
																</tr>
																<tr>
																	<td class="left"><?php echo $entry_slideshow_controlnav; ?></td>
																	<td class="left">
																		<select name="<?php echo $extension; ?>_module[<?php echo $module_row; ?>][slideshow_controlnav]">
																			<?php if ($data['slideshow_controlnav'] == 'true') { ?>
																				<option value="true" selected="selected"><?php echo $text_true; ?></option>
																				<option value="false"><?php echo $text_false; ?></option>
																			<?php } else { ?>
																				<option value="true"><?php echo $text_true; ?></option>
																				<option value="false" selected="selected"><?php echo $text_false; ?></option>
																			<?php } ?>
																		</select>
																	</td>
																</tr>
																<tr>
																	<td class="left"><?php echo $entry_slideshow_pauseonhover; ?></td>
																	<td class="left">
																		<select name="<?php echo $extension; ?>_module[<?php echo $module_row; ?>][slideshow_pauseonhover]">
																			<?php if ($data['slideshow_pauseonhover'] == 'true') { ?>
																				<option value="true" selected="selected"><?php echo $text_true; ?></option>
																				<option value="false"><?php echo $text_false; ?></option>
																			<?php } else { ?>
																				<option value="true"><?php echo $text_true; ?></option>
																				<option value="false" selected="selected"><?php echo $text_false; ?></option>
																			<?php } ?>
																		</select>
																	</td>
																</tr>
																<tr>
																	<td class="left"><?php echo $entry_slideshow_manualadvance; ?></td>
																	<td class="left">
																		<select name="<?php echo $extension; ?>_module[<?php echo $module_row; ?>][slideshow_manualadvance]">
																			<?php if ($data['slideshow_manualadvance'] == 'true') { ?>
																				<option value="true" selected="selected"><?php echo $text_true; ?></option>
																				<option value="false"><?php echo $text_false; ?></option>
																			<?php } else { ?>
																				<option value="true"><?php echo $text_true; ?></option>
																				<option value="false" selected="selected"><?php echo $text_false; ?></option>
																			<?php } ?>
																		</select>
																	</td>
																</tr>
																<tr>
																	<td class="left"><?php echo $entry_slideshow_prevtext; ?></td>
																	<td class="left">
																		<input type="text" name="<?php echo $extension; ?>_module[<?php echo $module_row; ?>][slideshow_prevtext]" value="<?php echo $data['slideshow_prevtext']; ?>" size="5" />
																	</td>
																</tr>
																<tr>
																	<td class="left"><?php echo $entry_slideshow_nexttext; ?></td>
																	<td class="left">
																		<input type="text" name="<?php echo $extension; ?>_module[<?php echo $module_row; ?>][slideshow_nexttext]" value="<?php echo $data['slideshow_nexttext']; ?>" size="5" />
																	</td>
																</tr>
																<tr>
																	<td class="left"><?php echo $entry_slideshow_randomstart; ?></td>
																	<td class="left">
																		<select name="<?php echo $extension; ?>_module[<?php echo $module_row; ?>][slideshow_randomstart]">
																			<?php if ($data['slideshow_randomstart'] == 'true') { ?>
																				<option value="true" selected="selected"><?php echo $text_true; ?></option>
																				<option value="false"><?php echo $text_false; ?></option>
																			<?php } else { ?>
																				<option value="true"><?php echo $text_true; ?></option>
																				<option value="false" selected="selected"><?php echo $text_false; ?></option>
																			<?php } ?>
																		</select>
																	</td>
																</tr>
															</tbody>
														</table>
													</div>
												</div>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
						<?php $module_row++; ?>
					<?php } ?>
				<?php } ?>
				<div id="oca-foot"></div>
				<div class="oca-rate">
					<div class="oca-footer">
						<div class="buttons"><a onclick="$('#form').attr('action', '<?php echo $action; ?>&apply=true'); $('#form').submit();" class="button"><span><?php echo $button_apply; ?></span></a> <a onclick="addModule();" class="button"><span><?php echo $button_add_module; ?></span></a></div>
					</div>
				</div>
			</form>
			<center><?php echo $text_contact; ?></center>
		</div>
	</div>
</div>

<script type="text/javascript"><!--
var module_row = <?php echo $module_row; ?>;

function addModule() {	
	html =	'<div id="module-row' + module_row + '" class="oca-rate">';
	html +=	' <div class="oca-head clearfix"><div class="oca-title"><?php echo $text_modules; ?></div> <div class="oca-remove"><a onclick="copyModule(' + module_row + ');"><img src="view/image/oca_copy.png" alt="<?php echo $button_copy_module; ?>" title="<?php echo $button_copy_module; ?>" /></a>&nbsp;&nbsp;<a onclick="if (confirm(\'<?php echo $text_confirm_delete; ?>\')) { $(\'#module-row' + module_row + '\').remove(); }"><img src="view/image/oca_remove.png" alt="<?php echo $button_remove_module; ?>" title="<?php echo $button_remove_module; ?>" /></a></div></div>';
	html +=	' <div class="oca-content">';
	html +=	'  <table class="oca-table">';
	html +=	'   <thead>';
	html +=	'    <tr>';
	html +=	'     <td class="left" colspan="3" style="border-bottom: 1px solid #DDDDDD;"><?php echo $entry_description; ?> <input type="text" name="<?php echo $extension; ?>_module[' + module_row + '][module_name]" value="<?php echo $text_modules; ?>" size="115" /></td>';
	html +=	'    </tr>';
	html +=	'    <tr>';
	html +=	'     <td class="center"><?php echo $text_general; ?></td>';
	html +=	'     <td class="center"><?php echo $text_layout; ?></td>';
	html +=	'     <td class="center"><?php echo $text_banner; ?></td>';
	html +=	'    </tr>';
	html +=	'   </thead>';
	html +=	'   <tbody>';
	html +=	'    <tr>';
	html +=	'     <td class="center" style="vertical-align:top;">';
	html +=	'      <div class="oca-entry"><?php echo $entry_status; ?></div>';
	html +=	'      <div class="oca-input">';
	html +=	'       <select name="<?php echo $extension; ?>_module[' + module_row + '][status]">';
	html +=	'        <option value="1" selected="selected"><?php echo $text_enabled; ?></option>';
	html +=	'        <option value="0"><?php echo $text_disabled; ?></option>';
	html +=	'       </select>';
	html +=	'      </div>';
	html +=	'      <div class="oca-entry"><?php echo $entry_cache; ?></div>';
	html +=	'      <div class="oca-input">';
	html +=	'       <select name="<?php echo $extension; ?>_module[' + module_row + '][cache]">';
	html +=	'        <option value="1" selected="selected"><?php echo $text_enabled; ?></option>';
	html +=	'        <option value="0"><?php echo $text_disabled; ?></option>';
	html +=	'       </select>';
	html +=	'      </div>';
	html +=	'      <div class="oca-entry"><?php echo $entry_sort_order; ?></div>';
	html +=	'      <div class="oca-input">';
	html +=	'       <input type="text" name="<?php echo $extension; ?>_module[' + module_row + '][sort_order]" value="0" size="5" />';
	html +=	'      </div>';
	html +=	'      <div class="oca-entry"><?php echo $entry_stores; ?></div>';
	html +=	'      <div class="oca-input">';
	html +=	'       <div class="scrollbox">';
					 <?php $class = 'even'; ?>
	html +=	'        <div class="<?php echo $class; ?>"><input type="checkbox" name="<?php echo $extension; ?>_module[' + module_row + '][stores][]" value="0" checked="checked" /><?php echo str_replace("'", "&#39;", $this->config->get('config_name')); ?></div>';
					 <?php foreach ($stores as $store) { ?>
					 <?php $class = ($class == 'even') ? 'odd' : 'even'; ?>
	html +=	'         <div class="<?php echo $class; ?>"><input type="checkbox" name="<?php echo $extension; ?>_module[' + module_row + '][stores][]" value="<?php echo $store['store_id']; ?>" checked="checked" /><?php echo str_replace("'", "&#39;", $store['name']); ?></div>';
					 <?php } ?>
	html +=	'       </div>';
	html +=	'       <a onclick="$(this).parent().find(\':checkbox\').attr(\'checked\', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(\':checkbox\').attr(\'checked\', false);"><?php echo $text_unselect_all; ?></a>';
	html +=	'      </div>';													
	html +=	'      <div class="oca-entry"><?php echo $entry_customer_groups; ?></div>';
	html +=	'      <div class="oca-input">';
	html +=	'       <div class="scrollbox">';
					 <?php $class = 'even'; ?>
	html +=	'        <div class="<?php echo $class; ?>"><input type="checkbox" name="<?php echo $extension; ?>_module[' + module_row + '][customer_groups][]" value="0" checked="checked" /><i><?php echo $text_guest_checkout; ?></i></div>';
					 <?php foreach ($customer_groups as $customer_group) { ?>
					  <?php $class = ($class == 'even') ? 'odd' : 'even'; ?>
	html +=	'         <div class="<?php echo $class; ?>"><input type="checkbox" name="<?php echo $extension; ?>_module[' + module_row + '][customer_groups][]" value="<?php echo $customer_group['customer_group_id']; ?>" checked="checked" /><?php echo str_replace("'", "&#39;", $customer_group['name']); ?></div>';
					 <?php } ?>
	html +=	'       </div>';
	html +=	'       <a onclick="$(this).parent().find(\':checkbox\').attr(\'checked\', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(\':checkbox\').attr(\'checked\', false);"><?php echo $text_unselect_all; ?></a>';
	html +=	'      </div>';
	html +=	'      <div class="oca-entry"><?php echo $entry_dates; ?></div>';
	html +=	'      <div class="oca-input" align="center">';
	html +=	'       <table class="oca-table-noborder" style="width: 150px;">';
	html +=	'        <thead>';
	html +=	'         <tr><td class="center"><?php echo $text_start; ?></td><td class="center"><?php echo $text_end; ?></td></tr>';
	html +=	'        </thead>';
	html +=	'        <tbody>';
	html +=	'         <tr>';
	html +=	'          <td class="center"><input type="text" name="<?php echo $extension; ?>_module[' + module_row + '][date_start]" value="" class="date" size="8" /></td>';
	html +=	'          <td class="center"><input type="text" name="<?php echo $extension; ?>_module[' + module_row + '][date_end]" value="" class="date"  size="8" /></td>';
	html +=	'         </tr>';
	html +=	'        </tbody>';
	html +=	'       </table>';
	html +=	'      </div>';
	html +=	'     </td>';
	html +=	'     <td class="center" style="vertical-align:top;">';
	html +=	'      <div class="oca-entry"><?php echo $entry_categories; ?></div>';
	html +=	'      <div class="oca-input">';
	html +=	'       <div class="scrollbox" style="height:150px;">';
					 <?php $class = 'even'; ?>
	html +=	'        <div class="<?php echo $class; ?>"><input type="checkbox" name="<?php echo $extension; ?>_module[' + module_row + '][categories][]" value="0" checked="checked" /><i><?php echo $text_all_categories; ?></i></div>';
					 <?php foreach ($categories as $category) { ?>
					  <?php $class = ($class == 'even') ? 'odd' : 'even'; ?>
	html +=	'         <div class="<?php echo $class; ?>"><input type="checkbox" name="<?php echo $extension; ?>_module[' + module_row + '][categories][]" value="<?php echo $category['category_id']; ?>" /><?php echo str_replace("'", "&#39;", $category['name']); ?></div>';
					 <?php } ?>
	html +=	'       </div>';
	html +=	'       <a onclick="$(this).parent().find(\':checkbox\').attr(\'checked\', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(\':checkbox\').attr(\'checked\', false);"><?php echo $text_unselect_all; ?></a>';
	html +=	'      </div>';
	html +=	'      <div class="oca-entry">Бренды</div>';
	html +=	'      <div class="oca-input">';
	html +=	'       <div class="scrollbox" style="height:150px;">';
					 <?php $class = 'even'; ?>
	html +=	'        <div class="<?php echo $class; ?>"><input type="checkbox" name="<?php echo $extension; ?>_module[' + module_row + '][manufacturers][]" value="0" checked="checked" /><i>Все Бренды</i></div>';
					 <?php foreach ($manufacturers as $manufacturer) { ?>
					  <?php $class = ($class == 'even') ? 'odd' : 'even'; ?>
	html +=	'         <div class="<?php echo $class; ?>"><input type="checkbox" name="<?php echo $extension; ?>_module[' + module_row + '][manufacturers][]" value="<?php echo $manufacturer['manufacturer_id']; ?>" /><?php echo str_replace("'", "&#39;", $manufacturer['name']); ?></div>';
					 <?php } ?>
	html +=	'       </div>';
	html +=	'       <a onclick="$(this).parent().find(\':checkbox\').attr(\'checked\', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(\':checkbox\').attr(\'checked\', false);"><?php echo $text_unselect_all; ?></a>';
	html +=	'      </div>';
	html +=	'      <div class="oca-entry"><?php echo $entry_languages; ?></div>';
	html +=	'      <div class="oca-input">';
	html +=	'       <div class="scrollbox">';
					 <?php $class = 'even'; ?>
	html +=	'        <div class="<?php echo $class; ?>"><input type="checkbox" name="<?php echo $extension; ?>_module[' + module_row + '][languages][]" value="0" checked="checked" /><?php echo $text_all_languages; ?></div>';
					 <?php foreach ($languages as $language) { ?>
					  <?php $class = ($class == 'even') ? 'odd' : 'even'; ?>
	html +=	'         <div class="<?php echo $class; ?>"><input type="checkbox" name="<?php echo $extension; ?>_module[' + module_row + '][languages][]" value="<?php echo $language['code']; ?>" checked="checked" /><?php echo str_replace("'", "&#39;", $language['name']); ?></div>';
					 <?php } ?>
	html +=	'       </div>';
	html +=	'       <a onclick="$(this).parent().find(\':checkbox\').attr(\'checked\', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(\':checkbox\').attr(\'checked\', false);"><?php echo $text_unselect_all; ?></a>';
	html +=	'      </div>';
	html +=	'      <div class="oca-entry"><?php echo $entry_layout; ?></div>';
	html +=	'      <div class="oca-input" align="center">';
	html +=	'       <select name="<?php echo $extension; ?>_module[' + module_row + '][layout_id]">';
					 <?php foreach ($layouts as $layout) { ?>
	html +=	'         <option value="<?php echo $layout['layout_id']; ?>"><?php echo str_replace("'", "&#39;", $layout['name']); ?></option>';
					 <?php } ?>
	html +=	'       </select>';
	html +=	'      </div>';
	html +=	'      <div class="oca-entry"><?php echo $entry_position; ?></div>';
	html +=	'      <div class="oca-input">';
	html +=	'       <select name="<?php echo $extension; ?>_module[' + module_row + '][position]">';
					 <?php foreach ($positions as $position) { ?>
	html +=	'         <option value="<?php echo $position['id']; ?>"><?php echo $position['name']; ?></option>';
					 <?php } ?>
	html +=	'       </select>';
	html +=	'      </div>';
	html +=	'     </td>';
	html +=	'     <td class="center" style="vertical-align:top;">';
	html +=	'      <div class="oca-entry"><?php echo $entry_banners; ?></div>';
	html +=	'      <div class="oca-input">';
	html +=	'       <table class="oca-table-noborder sortable" style="width: 100%;">';
	html +=	'        <thead>';
	html +=	'         <tr>';
	html +=	'          <td class="center">&nbsp;</td>';
	html +=	'          <td class="center"><?php echo $entry_banner; ?></td>';
	html +=	'          <td class="center" colspan="2"><?php echo $entry_dimension; ?></td>';
	html +=	'          <td class="center" colspan="4"><?php echo $entry_margin; ?></td>';
	html +=	'          <td class="center"><?php echo $entry_float; ?></td>';
	html +=	'          <td class="center"><?php echo $entry_clear; ?></td>';
	html +=	'          <td class="center">&nbsp;</td>';
	html +=	'         </tr>';
	html +=	'         <tr style="border-bottom: 1px solid #DDDDDD;">';
	html +=	'          <td class="center">&nbsp;</td>';
	html +=	'          <td class="center">&nbsp;</td>';
	html +=	'          <td class="center"><?php echo $text_width; ?></td>';
	html +=	'          <td class="center"><?php echo $text_height; ?></td>';
	html +=	'          <td class="center"><?php echo $text_left; ?></td>';
	html +=	'          <td class="center"><?php echo $text_right; ?></td>';
	html +=	'          <td class="center"><?php echo $text_top; ?></td>';
	html +=	'          <td class="center"><?php echo $text_bottom; ?></td>';
	html +=	'          <td class="center">&nbsp;</td>';
	html +=	'          <td class="center">&nbsp;</td>';
	html +=	'          <td class="center">&nbsp;</td>';
	html +=	'         </tr>';
	html +=	'        </thead>';
	html +=	'        <tbody class="sortable">';
	html +=	'         <tr id="banner-row' + banner_row + '" style="border-bottom: 1px solid #DDDDDD;">';
	html +=	'          <td class="center"><div class="oca-sort"><img src="view/image/oca_sort.gif" alt="<?php echo $button_move_banner; ?>" /></div></td>';
	html +=	'          <td class="center">';
	html +=	'           <select name="<?php echo $extension; ?>_module[' + module_row + '][banners][' + banner_row + '][banner_id]">';
						 <?php foreach ($banners as $banner) { ?>
	html +=	'             <option value="<?php echo $banner['banner_id']; ?>"><?php echo str_replace("'", "&#39;", $banner['name']); ?></option>';
						 <?php } ?>
	html +=	'           </select>';
	html +=	'          </td>';
	html +=	'          <td class="center"><input type="text" name="<?php echo $extension; ?>_module[' + module_row + '][banners][' + banner_row + '][image_width]" value="980" size="5" /></td>';
	html +=	'          <td class="center"><input type="text" name="<?php echo $extension; ?>_module[' + module_row + '][banners][' + banner_row + '][image_height]" value="200" size="5" /></td>';
	html +=	'          <td class="center"><input type="text" name="<?php echo $extension; ?>_module[' + module_row + '][banners][' + banner_row + '][margin_left]" value="5" size="5" /></td>';
	html +=	'          <td class="center"><input type="text" name="<?php echo $extension; ?>_module[' + module_row + '][banners][' + banner_row + '][margin_right]" value="5" size="5" /></td>';
	html +=	'          <td class="center"><input type="text" name="<?php echo $extension; ?>_module[' + module_row + '][banners][' + banner_row + '][margin_top]" value="5" size="5" /></td>';
	html +=	'          <td class="center"><input type="text" name="<?php echo $extension; ?>_module[' + module_row + '][banners][' + banner_row + '][margin_bottom]" value="5" size="5" /></td>';
	html +=	'          <td class="center">';
	html +=	'           <select name="<?php echo $extension; ?>_module[' + module_row + '][banners][' + banner_row + '][float]">';
						 <?php foreach ($floats as $float) { ?>
	html +=	'             <option value="<?php echo $float; ?>"><?php echo $float; ?></option>';
						 <?php } ?>
	html +=	'           </select>';
	html +=	'          </td>';
	html +=	'          <td class="center">';
	html +=	'           <select name="<?php echo $extension; ?>_module[' + module_row + '][banners][' + banner_row + '][clear]">';
						 <?php foreach ($clears as $clear) { ?>
	html +=	'             <option value="<?php echo $clear; ?>"><?php echo $clear; ?></option>';
						 <?php } ?>
	html +=	'           </select>';
	html +=	'          </td>';
	html +=	'          <td class="right" style="vertical-align: middle;"><a onclick="$(\'#banner-row' + banner_row + '\').remove();"><img src="view/image/delete.png" alt="<?php echo $button_remove_banner; ?>" /></a></td>';
	html +=	'         </tr>';
	html +=	'         <tr id="module-row' + module_row + '-banner"><td class="right" colspan="11" style="vertical-align: middle;"><a onclick="addBanner(' + module_row + ')"><img src="view/image/add.png" alt="<?php echo $button_add_banner; ?>" /></a></td></tr>';
	html +=	'        </tbody>';
	html +=	'       </table>';
	html +=	'      </div>';
	html +=	'      <div class="oca-entry"><?php echo $entry_display; ?></div>';
	html +=	'      <div class="oca-input">';
	html +=	'       <select name="<?php echo $extension; ?>_module[' + module_row + '][display_type]" onChange="toggleDisplayType(' + module_row + ', $(this).val())">';
					 <?php foreach ($display_types as $display_type) { ?>
	html +=	'         <option value="<?php echo $display_type['id']; ?>"><?php echo $display_type['name']; ?></option>';
					 <?php } ?>
	html +=	'       </select>';
	html +=	'      </div>';
	html +=	'      <div id="banner' + module_row + '">';
	html +=	'       <div class="oca-entry"><?php echo $entry_banner_settings; ?></div>';
	html +=	'       <div class="oca-input">';
	html +=	'        <table class="oca-table-noborder">';
	html +=	'         <thead>';
	html +=	'          <tr>';
	html +=	'           <td class="left"><?php echo $text_option; ?></td>';
	html +=	'           <td class="left"><?php echo $text_value; ?></td>';
	html +=	'          </tr>';
	html +=	'         </thead>';
	html +=	'         <tbody>';
	html +=	'          <tr>';
	html +=	'           <td class="left"><?php echo $entry_banner_effect; ?></td>';
	html +=	'           <td class="left">';
	html +=	'            <select name="<?php echo $extension; ?>_module[' + module_row + '][banner_effect]">';
						  <?php foreach ($banner_effects as $banner_effect) { ?>
	html +=	'              <option value="<?php echo $banner_effect; ?>"><?php echo $banner_effect; ?></option>';
						  <?php } ?>
	html +=	'            </select>';
	html +=	'           </td>';
	html +=	'          </tr>';
	html +=	'          <tr>';
	html +=	'           <td class="left"><?php echo $entry_banner_speed; ?></td>';
	html +=	'           <td class="left">';
	html +=	'            <input type="text" name="<?php echo $extension; ?>_module[' + module_row + '][banner_speed]" value="500" size="5" />';
	html +=	'           </td>';
	html +=	'          </tr>';
	html +=	'          <tr>';
	html +=	'           <td class="left"><?php echo $entry_banner_timeout; ?></td>';
	html +=	'           <td class="left">';
	html +=	'            <input type="text" name="<?php echo $extension; ?>_module[' + module_row + '][banner_timeout]" value="4000" size="5" />';
	html +=	'           </td>';
	html +=	'          </tr>';
	html +=	'          <tr>';
	html +=	'           <td class="left"><?php echo $entry_banner_pause; ?></td>';
	html +=	'           <td class="left">';
	html +=	'            <select name="<?php echo $extension; ?>_module[' + module_row + '][banner_pause]">';
	html +=	'             <option value="1" selected="selected"><?php echo $text_true; ?></option>';
	html +=	'             <option value="0"><?php echo $text_false; ?></option>';
	html +=	'            </select>';
	html +=	'           </td>';
	html +=	'          </tr>';
	html +=	'         </tbody>';
	html +=	'        </table>';
	html +=	'       </div>';
	html +=	'      </div>';
	html +=	'      <div id="slideshow' + module_row + '" style="display: none;">';
	html +=	'       <div class="oca-entry"><?php echo $entry_slideshow_settings; ?></div>';
	html +=	'       <div class="oca-input">';
	html +=	'        <table class="oca-table-noborder">';
	html +=	'         <thead>';
	html +=	'          <tr>';
	html +=	'           <td class="left"><?php echo $text_option; ?></td>';
	html +=	'           <td class="left"><?php echo $text_value; ?></td>';
	html +=	'          </tr>';
	html +=	'         </thead>';
	html +=	'         <tbody>';
	html +=	'          <tr>';
	html +=	'           <td class="left"><?php echo $entry_slideshow_effect; ?></td>';
	html +=	'           <td class="left">';
	html +=	'            <select name="<?php echo $extension; ?>_module[' + module_row + '][slideshow_effect]">';
						  <?php foreach ($slideshow_effects as $slideshow_effect) { ?>
	html +=	'              <option value="<?php echo $slideshow_effect; ?>"><?php echo $slideshow_effect; ?></option>';
						  <?php } ?>
	html +=	'            </select>';
	html +=	'           </td>';
	html +=	'          </tr>';
	html +=	'          <tr>';
	html +=	'           <td class="left"><?php echo $entry_slideshow_animspeed; ?></td>';
	html +=	'           <td class="left">';
	html +=	'            <input type="text" name="<?php echo $extension; ?>_module[' + module_row + '][slideshow_animspeed]" value="500" size="5" />';
	html +=	'           </td>';
	html +=	'          </tr>';
	html +=	'          <tr>';
	html +=	'           <td class="left"><?php echo $entry_slideshow_pausetime; ?></td>';
	html +=	'           <td class="left">';
	html +=	'            <input type="text" name="<?php echo $extension; ?>_module[' + module_row + '][slideshow_pausetime]" value="4000" size="5" />';
	html +=	'           </td>';
	html +=	'          </tr>';
	html +=	'          <tr>';
	html +=	'           <td class="left"><?php echo $entry_slideshow_startslide; ?></td>';
	html +=	'           <td class="left">';
	html +=	'            <input type="text" name="<?php echo $extension; ?>_module[' + module_row + '][slideshow_startslide]" value="0" size="5" />';
	html +=	'           </td>';
	html +=	'          </tr>';
	html +=	'          <tr>';
	html +=	'           <td class="left"><?php echo $entry_slideshow_directionnav; ?></td>';
	html +=	'           <td class="left">';
	html +=	'            <select name="<?php echo $extension; ?>_module[' + module_row + '][slideshow_directionnav]">';
	html +=	'             <option value="true" selected="selected"><?php echo $text_true; ?></option>';
	html +=	'             <option value="false"><?php echo $text_false; ?></option>';
	html +=	'            </select>';
	html +=	'           </td>';
	html +=	'          </tr>';
	html +=	'          <tr>';
	html +=	'           <td class="left"><?php echo $entry_slideshow_controlnav; ?></td>';
	html +=	'           <td class="left">';
	html +=	'            <select name="<?php echo $extension; ?>_module[' + module_row + '][slideshow_controlnav]">';
	html +=	'             <option value="true" selected="selected"><?php echo $text_true; ?></option>';
	html +=	'             <option value="false"><?php echo $text_false; ?></option>';
	html +=	'            </select>';
	html +=	'           </td>';
	html +=	'          </tr>';
	html +=	'          <tr>';
	html +=	'           <td class="left"><?php echo $entry_slideshow_pauseonhover; ?></td>';
	html +=	'           <td class="left">';
	html +=	'            <select name="<?php echo $extension; ?>_module[' + module_row + '][slideshow_pauseonhover]">';
	html +=	'             <option value="true" selected="selected"><?php echo $text_true; ?></option>';
	html +=	'             <option value="false"><?php echo $text_false; ?></option>';
	html +=	'            </select>';
	html +=	'           </td>';
	html +=	'          </tr>';
	html +=	'          <tr>';
	html +=	'           <td class="left"><?php echo $entry_slideshow_manualadvance; ?></td>';
	html +=	'           <td class="left">';
	html +=	'            <select name="<?php echo $extension; ?>_module[' + module_row + '][slideshow_manualadvance]">';
	html +=	'             <option value="true"><?php echo $text_true; ?></option>';
	html +=	'             <option value="false" selected="selected"><?php echo $text_false; ?></option>';
	html +=	'            </select>';
	html +=	'           </td>';
	html +=	'          </tr>';
	html +=	'          <tr>';
	html +=	'           <td class="left"><?php echo $entry_slideshow_prevtext; ?></td>';
	html +=	'           <td class="left">';
	html +=	'            <input type="text" name="<?php echo $extension; ?>_module[' + module_row + '][slideshow_prevtext]" value="Prev" size="5" />';
	html +=	'           </td>';
	html +=	'          </tr>';
	html +=	'          <tr>';
	html +=	'           <td class="left"><?php echo $entry_slideshow_nexttext; ?></td>';
	html +=	'           <td class="left">';
	html +=	'            <input type="text" name="<?php echo $extension; ?>_module[' + module_row + '][slideshow_nexttext]" value="Next" size="5" />';
	html +=	'           </td>';
	html +=	'          </tr>';
	html +=	'          <tr>';
	html +=	'           <td class="left"><?php echo $entry_slideshow_randomstart; ?></td>';
	html +=	'           <td class="left">';
	html +=	'            <select name="<?php echo $extension; ?>_module[' + module_row + '][slideshow_randomstart]">';
	html +=	'             <option value="true"><?php echo $text_true; ?></option>';
	html +=	'             <option value="false" selected="selected"><?php echo $text_false; ?></option>';
	html +=	'            </select>';
	html +=	'           </td>';
	html +=	'          </tr>';
	html +=	'         </tbody>';
	html +=	'        </table>';
	html +=	'       </div>';
	html +=	'      </div>';
	html +=	'     </td>';
	html +=	'    </tr>';
	html +=	'   </tbody>';
	html +=	'  </table>';
	html +=	' </div>';
	html +=	'</div>';
	
	$('#oca-foot').before(html);
	
	$('.date').datepicker({dateFormat: 'yy-mm-dd'});
	
	$(".sortable > tbody").sortable({
		placeholder: "oca-sort-highlight",
		handle: ".oca-sort",
		items: "> tr:not(:last)",
		cursor: "move"
	});
	
	module_row++;
	banner_row++;
}
//--></script>

<?php if ($ocversion >= 150) { ?>
	<script type="text/javascript"><!--	
		function copyModule(copied_module) {
			html = $('#module-row'+ copied_module).clone(true);
			html.find(':input').attr('name', function(i, name) {
				return name.replace('['+ copied_module +']', '['+ module_row +']');
			});
			html.find(':input').attr('name', function(i, name) {
				return name.replace('[banners][', '[banners][x');
			});
			html.find('[id]').attr('id', function(i, name) {
				return name.replace(copied_module, module_row);
			});
			html.find('[onclick]').attr('onclick', function(i, name) {
				return name.replace(copied_module, module_row);
			});
			html.find('[onchange]').attr('onchange', function(i, name) {
				return name.replace(copied_module, module_row);
			});
			html.find('.oca-title').append('-Copy');
			html.attr('id', 'module-row'+ module_row);
			html.find('.ui-sortable').attr("class", "").removeClass('ui-sortable').removeData('sortable').unbind().sortable({placeholder: "oca-sort-highlight", handle: ".oca-sort", items: "> tr:not(:last)", cursor: "move"});
			
			<?php if ($ocversion >= 150) { ?>
				html.find('input.date').attr("id", "").removeClass('hasDatepicker').removeData('datepicker').unbind().datepicker({dateFormat: 'yy-mm-dd'});
			<?php } ?>
			
			$('#oca-foot').before(html);
			
			module_row++;
		}
	//--></script>
<?php } ?>

<script type="text/javascript"><!--
var banner_row = <?php echo $banner_row; ?>;

function addBanner(banner_module_row) {
	html =	'<tr id="banner-row' + banner_row + '" style="border-bottom: 1px solid #DDDDDD;">';
	html +=	' <td class="center"><div class="oca-sort"><img src="view/image/oca_sort.gif" alt="<?php echo $button_move_banner; ?>" /></div></td>';
	html +=	' <td class="center">';
	html +=	'  <select name="<?php echo $extension; ?>_module[' + banner_module_row + '][banners][' + banner_row + '][banner_id]">';
				<?php foreach ($banners as $banner) { ?>
	html +=	'    <option value="<?php echo $banner['banner_id']; ?>"><?php echo str_replace("'", "&#39;", $banner['name']); ?></option>';
				<?php } ?>
	html +=	'  </select>';
	html +=	' </td>';
	html +=	' <td class="center"><input type="text" name="<?php echo $extension; ?>_module[' + banner_module_row + '][banners][' + banner_row + '][image_width]" value="980" size="5" /></td>';
	html +=	' <td class="center"><input type="text" name="<?php echo $extension; ?>_module[' + banner_module_row + '][banners][' + banner_row + '][image_height]" value="200" size="5" /></td>';
	html +=	' <td class="center"><input type="text" name="<?php echo $extension; ?>_module[' + banner_module_row + '][banners][' + banner_row + '][margin_left]" value="5" size="5" /></td>';
	html +=	' <td class="center"><input type="text" name="<?php echo $extension; ?>_module[' + banner_module_row + '][banners][' + banner_row + '][margin_right]" value="5" size="5" /></td>';
	html +=	' <td class="center"><input type="text" name="<?php echo $extension; ?>_module[' + banner_module_row + '][banners][' + banner_row + '][margin_top]" value="5" size="5" /></td>';
	html +=	' <td class="center"><input type="text" name="<?php echo $extension; ?>_module[' + banner_module_row + '][banners][' + banner_row + '][margin_bottom]" value="5" size="5" /></td>';
	html +=	' <td class="center">';
	html +=	'  <select name="<?php echo $extension; ?>_module[' + banner_module_row + '][banners][' + banner_row + '][float]">';
				<?php foreach ($floats as $float) { ?>
	html +=	'    <option value="<?php echo $float; ?>"><?php echo $float; ?></option>';
				<?php } ?>
	html +=	'  </select>';
	html +=	' </td>';
	html +=	' <td class="center">';
	html +=	'  <select name="<?php echo $extension; ?>_module[' + banner_module_row + '][banners][' + banner_row + '][clear]">';
				<?php foreach ($clears as $clear) { ?>
	html +=	'    <option value="<?php echo $clear; ?>"><?php echo $clear; ?></option>';
				<?php } ?>
	html +=	'  </select>';
	html +=	' </td>';
	html +=	' <td class="right" style="vertical-align: middle;"><a onclick="$(\'#banner-row' + banner_row + '\').remove();"><img src="view/image/delete.png" alt="<?php echo $button_remove_banner; ?>" /></a></td>';
	html +=	'</tr>';

	$('#module-row'+ banner_module_row +'-banner').before(html);
	
	banner_row++;
}
//--></script> 

<script type="text/javascript"><!--
	function toggleDisplayType(row, value) {
		if (value == 0) {
			$('#banner'+ row).fadeIn('slow');
		} else {
			$('#banner'+ row).hide();
		}
		if (value == 1) {
			$('#slideshow'+ row).fadeIn('slow');
		} else {
			$('#slideshow'+ row).hide();
		}
	}
	
	function toggleSlideshowEffects(row, value) {
		if (value.contains('slice')) {
			$('#slideshow' + row + '-effect-slice').fadeIn('slow');
		} else {
			$('#slideshow' + row + '-effect-slice').hide();
		}
		if (value.contains('box')) {
			$('#slideshow' + row + '-effect-box').fadeIn('slow');
		} else {
			$('#slideshow' + row + '-effect-box').hide();
		}
	}
	
	$(function() {
		$(".sortable > tbody").sortable({
			placeholder: "oca-sort-highlight",
			handle: ".oca-sort",
			items: "> tr:not(:last)",
			cursor: "move"
		});
	});

	jQuery(document).ready(function() {
		jQuery(".oca-content").hide();
		jQuery(".oca-title").click(function() {
			jQuery(this).parent().next(".oca-content").slideToggle(500);
		});
	});

	function expandAllModules() {
		jQuery(".oca-content").show();
	}
	
	function collapseAllModules() {
		jQuery(".oca-content").hide();
	}

    $(function () {
        $('#js-news').ticker({
            speed: 0.20,
            fadeInSpeed: 600,
            titleText: '<?php echo $text_tip; ?>'
        });
    });
//--></script>

<script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript"><!--
	$('.date').datepicker({dateFormat: 'yy-mm-dd'});
	$('.datetime').datetimepicker({
		dateFormat: 'yy-mm-dd',
		timeFormat: 'hh:mm'
	});		
	$('.time').timepicker({timeFormat: 'hh:mm'});	
//--></script> 

<?php echo $footer; ?> 
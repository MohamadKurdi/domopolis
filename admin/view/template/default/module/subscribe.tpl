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
	<div class="box">
		<div class="heading order_head">
			<h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
			<div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a></div>
		</div>
		<div class="content">
			<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
				<table id="module-settings" class="list">
					<thead>
						<tr>
							<td class="left"><?php echo $entry_id; ?></td>
							<td class="left"><?php echo $entry_email_alert; ?></td>
							<td class="left"><?php echo $entry_subscribe_confirm; ?></td>
							<td class="left"><?php echo $entry_layout; ?></td>
							<td class="left"><?php echo $entry_position; ?></td>
							<td class="left"><?php echo $entry_status; ?></td>
							<td class="right"><?php echo $entry_sort_order; ?></td>
							<td></td>
						</tr>
					</thead>
					<?php $module_row = 0; ?>
					<?php if(isset($modules)) { ?>
					<?php foreach ($modules as $module) { ?>
					<tbody id="module-row<?php echo $module_row; ?>">
						<tr>
							<td class="left"><?php echo $module_row; ?><input type="hidden" name="subscribe_module[<?php echo $module_row; ?>][subscribe_id]" value="<?php echo $module_row; ?>" /></td>
							<td class="left"><select name="subscribe_module[<?php echo $module_row; ?>][email_alert]">
									<?php if ($module['email_alert']) { ?>
									<option value="1" selected="selected"><?php echo $text_yes; ?></option>
									<option value="0"><?php echo $text_no; ?></option>
									<?php } else { ?>
									<option value="1"><?php echo $text_yes; ?></option>
									<option value="0" selected="selected"><?php echo $text_no; ?></option>
									<?php } ?>
								</select></td>
							<td class="left"><select name="subscribe_module[<?php echo $module_row; ?>][subscribe_confirm]">
									<?php if ($module['subscribe_confirm']) { ?>
									<option value="1" selected="selected"><?php echo $text_yes; ?></option>
									<option value="0"><?php echo $text_no; ?></option>
									<?php } else { ?>
									<option value="1"><?php echo $text_yes; ?></option>
									<option value="0" selected="selected"><?php echo $text_no; ?></option>
									<?php } ?>
								</select></td>
							<td class="left"><select name="subscribe_module[<?php echo $module_row; ?>][layout_id]">
									<option value=""><?php echo $text_no; ?></option>
									<?php foreach ($layouts as $layout) { ?>
									<?php if ($layout['layout_id'] == $module['layout_id']) { ?>
									<option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
									<?php } else { ?>
									<option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
									<?php } ?>
									<?php } ?>
								</select></td>
							<td class="left"><select name="subscribe_module[<?php echo $module_row; ?>][position]">
									<option value=""><?php echo $text_no; ?></option>
									<?php if ($module['position'] == 'content_top') { ?>
									<option value="content_top" selected="selected"><?php echo $text_content_top; ?></option>
									<?php } else { ?>
									<option value="content_top"><?php echo $text_content_top; ?></option>
									<?php } ?>
									<?php if ($module['position'] == 'content_bottom') { ?>
									<option value="content_bottom" selected="selected"><?php echo $text_content_bottom; ?></option>
									<?php } else { ?>
									<option value="content_bottom"><?php echo $text_content_bottom; ?></option>
									<?php } ?>
									<?php if ($module['position'] == 'column_left') { ?>
									<option value="column_left" selected="selected"><?php echo $text_column_left; ?></option>
									<?php } else { ?>
									<option value="column_left"><?php echo $text_column_left; ?></option>
									<?php } ?>
									<?php if ($module['position'] == 'column_right') { ?>
									<option value="column_right" selected="selected"><?php echo $text_column_right; ?></option>
									<?php } else { ?>
									<option value="column_right"><?php echo $text_column_right; ?></option>
									<?php } ?>
								</select></td>
							<td class="left"><select name="subscribe_module[<?php echo $module_row; ?>][status]">
									<?php if ($module['status']) { ?>
									<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
									<option value="0"><?php echo $text_disabled; ?></option>
									<?php } else { ?>
									<option value="1"><?php echo $text_enabled; ?></option>
									<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
									<?php } ?>
								</select></td>
							<td class="right"><input type="text" name="subscribe_module[<?php echo $module_row; ?>][sort_order]" value="<?php echo $module['sort_order']; ?>" size="3" /></td>
							<td class="right"><a onclick="$('#module-row<?php echo $module_row; ?>').remove();" class="button"><?php echo $button_remove; ?></a></td>
						</tr>
					</tbody>
					<?php $module_row++; ?>
					<?php } ?>
					<?php } ?>
					<tfoot>
						<tr>
							<td colspan="7"></td>
							<td class="right"><a onclick="addModule();" class="button"><?php echo $button_add_module; ?></a></td>
						</tr>
					</tfoot>
				</table>
			</form>
		</div>
	</div>
	<script type="text/javascript"><!--
		var module_row = <?php echo $module_row; ?>;
				function addModule() {
					html = '<tbody id="module-row'+module_row+'">';
					html += '  <tr>';
					html += '    <td class="left">'+module_row;
					html += '<input type="hidden" name="subscribe_module['+module_row+'][subscribe_id]" value="'+module_row+'" /></td>';
					html += '    <td class="left"><select name="subscribe_module['+module_row+'][email_alert]">';
					html += '      <option value="1" selected="selected"><?php echo $text_yes; ?></option>';
					html += '      <option value="0"><?php echo $text_no; ?></option>';
					html += '    </select></td>';
					html += '    <td class="left"><select name="subscribe_module['+module_row+'][subscribe_confirm]">';
					html += '      <option value="1" selected="selected"><?php echo $text_yes; ?></option>';
					html += '      <option value="0"><?php echo $text_no; ?></option>';
					html += '    </select></td>';
					html += '    <td class="left"><select name="subscribe_module['+module_row+'][layout_id]">';
					html += '    <option value=""><?php echo $text_no; ?></option>';
							<?php foreach ($layouts as $layout) { ?>
							html += '      <option value="<?php echo $layout['layout_id']; ?>"><?php echo addslashes($layout['name']); ?></option>';
							<?php } ?>
							html += '    </select></td>';
					html += '    <td class="left"><select name="subscribe_module['+module_row+'][position]">';
					html += '    <option value=""><?php echo $text_no; ?></option>';
					html += '      <option value="content_top"><?php echo $text_content_top; ?></option>';
					html += '      <option value="content_bottom"><?php echo $text_content_bottom; ?></option>';
					html += '      <option value="column_left"><?php echo $text_column_left; ?></option>';
					html += '      <option value="column_right"><?php echo $text_column_right; ?></option>';
					html += '    </select></td>';
					html += '    <td class="left"><select name="subscribe_module['+module_row+'][status]">';
					html += '      <option value="1" selected="selected"><?php echo $text_enabled; ?></option>';
					html += '      <option value="0"><?php echo $text_disabled; ?></option>';
					html += '    </select></td>';
					html += '    <td class="right"><input type="text" name="subscribe_module['+module_row+'][sort_order]" value="" size="3" /></td>';
					html += '    <td class="right"><a onclick="$(\'#module-row'+module_row+'\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
					html += '  </tr>';
					html += '</tbody>';
					$('#module-settings tfoot').before(html);
					module_row++;
				}
		//--></script>
	<?php echo $footer; ?>

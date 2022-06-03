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
			<h1><img src="view/image/pickup-advanced.png" alt="" /> <?php echo $heading_title; ?></h1>
			<div class="buttons">
				<a onClick="$('#form').submit();" class="button"><?php echo $button_save; ?></a>
				<a onClick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a>
			</div>
		</div>
		<div class="content">
			<div id="tabs" class="htabs">
				<a href="#tab-points"><?php echo $tab_points; ?></a>
				<a href="#tab-settings"><?php echo $tab_settings; ?></a>
				<div class="clr"></div>
			</div>
			<div class="th_style"></div>
			<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
				<div id="tab-points">
					<table id="module" class="list">
						<thead>
							<tr>
								<td style="display:none;"><?php echo $entry_image; ?></td>
								<td><?php echo $entry_description; ?></td>
								<td>Подсказка</td>
								<td>Убирать из тайтла</td>
								<td><?php echo $entry_link; ?></td>
								<td><?php echo $entry_link_text; ?></td>
								<td class="center">Опции</td>
								<td class="center"><?php echo $entry_geo_zone; ?></td>
							</tr>
						</thead>
						<?php $module_row = 0; ?>
						<?php foreach ($modules as $module) { ?>
							<tbody id="module-row<?php echo $module_row; ?>">
								<tr style="border-bottom:2px solid black;">
									<td style="display:none;">
										<div class="image" style="padding: 8px 8px 0px; width: 115px;">
											<?php if (empty($module['image'])) { ?>
												<img src="<?php echo $no_image; ?>" alt="" id="thumb<?php echo $module_row; ?>" />
												<input type="hidden" name="pickup_advanced_module[<?php echo $module_row; ?>][image]" value="" id="image<?php echo $module_row; ?>" />
												<?php } else { ?>
												<img src="<?php echo $this->model_tool_image->resize($module['image'], 47, 47); ?>" alt="" id="thumb<?php echo $module_row; ?>" />
												<input type="hidden" name="pickup_advanced_module[<?php echo $module_row; ?>][image]" value="<?php echo $module['image']; ?>" id="image<?php echo $module_row; ?>" />
											<?php } ?>
											<span style="float: right; margin-top: 8px; margin-left: 8px;">
												<a onClick="imageUpload('image<?php echo $module_row; ?>', 'thumb<?php echo $module_row; ?>');"><?php echo $text_browse; ?></a>
												<br />
												<a onClick="$('#thumb<?php echo $module_row; ?>').attr('src', '<?php echo $no_image; ?>'); $('#image<?php echo $module_row; ?>').attr('value', '');"><?php echo $text_clear; ?></a>
											</span>
										</div>
									</td>
									<td style="width: 185px;">
										<?php foreach ($languages as $language) { ?>
											<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" style="margin-bottom: -1px;" /><br />
											<textarea name="pickup_advanced_module[<?php echo $module_row; ?>][<?php echo $language['language_id']; ?>][description]"><?php echo isset($module[$language['language_id']]) ? $module[$language['language_id']]['description'] : ''; ?></textarea>                        
											<br />
										<?php } ?>
									</td>
									<td style="width: 185px;">
										<?php foreach ($languages as $language) { ?>
											<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" style="margin-bottom: -1px;" /><br />
											<textarea name="pickup_advanced_module[<?php echo $module_row; ?>][<?php echo $language['language_id']; ?>][tip]"><?php echo isset($module[$language['language_id']]['tip']) ? $module[$language['language_id']]['tip'] : ''; ?></textarea>                        
											<br />
										<?php } ?>
									</td>
									<td style="width: 185px;">
										<?php foreach ($languages as $language) { ?>
											<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" style="margin-bottom: -1px;" />
											<br />
											<input type="text" name="pickup_advanced_module[<?php echo $module_row; ?>][<?php echo $language['language_id']; ?>][remove_title]" value="<?php echo isset($module[$language['language_id']]['remove_title']) ? $module[$language['language_id']]['remove_title'] : ''; ?>" style="margin-bottom: 3px;" size="19" />
											
										<?php } ?>
									</td>
									<td style="width: 185px;">
										<?php foreach ($languages as $language) { ?>
											<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" style="margin-bottom: -1px;" />
											<br />
											<textarea name="pickup_advanced_module[<?php echo $module_row; ?>][<?php echo $language['language_id']; ?>][link]"><?php echo isset($module[$language['language_id']]) ? $module[$language['language_id']]['link'] : ''; ?></textarea>
											
										<?php } ?>
									</td>
									<td style="width: 165px;">
										
										<?php foreach ($languages as $language) { ?>
											<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" style="margin-bottom: -1px;" />
											<br />
											<textarea name="pickup_advanced_module[<?php echo $module_row; ?>][<?php echo $language['language_id']; ?>][link_text]"><?php echo isset($module[$language['language_id']]) ? $module[$language['language_id']]['link_text'] : ''; ?></textarea>				  				                    
											
										<?php } ?>
										
										<br /><br />
										<?php echo $entry_link_status; ?><br />
										<select name="pickup_advanced_module[<?php echo $module_row; ?>][link_status]">
											<?php if ($module['link_status']) { ?>
												<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
												<option value="0"><?php echo $text_disabled; ?></option>
												<?php } else { ?>
												<option value="1"><?php echo $text_enabled; ?></option>
												<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
											<?php } ?>
										</select>
									</td>
									<td style="width: 115px;" class="center">
										<?php echo $entry_cost; ?><br />
										<input type="text" name="pickup_advanced_module[<?php echo $module_row; ?>][cost]" value="<?php echo $module['cost']; ?>" size="10" />
										
										<?php echo $entry_weight; ?><br />
										<select name="pickup_advanced_module[<?php echo $module_row; ?>][weight]">
											<?php if ($module['weight']) { ?>
												<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
												<option value="0"><?php echo $text_disabled; ?></option>
												<?php } else { ?>
												<option value="1"><?php echo $text_enabled; ?></option>
												<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
											<?php } ?>
										</select>
										
										<br /><br />
										<?php echo $entry_relation; ?><br />
										<select name="pickup_advanced_module[<?php echo $module_row; ?>][relation]">
											<?php if ($module['relation']) { ?>
												<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
												<option value="0"><?php echo $text_disabled; ?></option>
												<?php } else { ?>
												<option value="1"><?php echo $text_enabled; ?></option>
												<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
											<?php } ?>
										</select>
										
										<br /><br />
										<?php echo $entry_percentage; ?><br />					
										<select name="pickup_advanced_module[<?php echo $module_row; ?>][percentage]">
											<?php if ($module['percentage']) { ?>
												<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
												<option value="0"><?php echo $text_disabled; ?></option>
												<?php } else { ?>
												<option value="1"><?php echo $text_enabled; ?></option>
												<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
											<?php } ?>
										</select>
										
										<br /><br />
										<?php echo $entry_display_threshold; ?><br />
										<input type="text" name="pickup_advanced_module[<?php echo $module_row; ?>][display_threshold]" value="<?php echo $module['display_threshold']; ?>" size="7" />
										
										<br /><br />
										
										<?php echo $entry_status_text; ?><br />
										<select name="pickup_advanced_module[<?php echo $module_row; ?>][status]">
											<?php if ($module['status']) { ?>
												<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
												<option value="0"><?php echo $text_disabled; ?></option>
												<?php } else { ?>
												<option value="1"><?php echo $text_enabled; ?></option>
												<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
											<?php } ?>
										</select>
										
										<br /><br />
										Сортировка<br />
										<input type="text" name="pickup_advanced_module[<?php echo $module_row; ?>][sort_order]" value="<?php echo $module['sort_order']; ?>" size="1" />
										
									</td>
									<td style="width: 250px;" class="center">
										<select name="pickup_advanced_module[<?php echo $module_row; ?>][geo_zone_id]">
											<option value="0"><?php echo $text_all_zones; ?></option>
											<?php foreach ($geo_zones as $geo_zone) { ?>
												<?php if ($geo_zone['geo_zone_id'] == $module['geo_zone_id']) { ?>
													<option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
													<?php } else { ?>
													<option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
												<?php } ?>
											<?php } ?>
										</select>
										
										<br /><br />
										Город<br />
										<input type="text" name="pickup_advanced_module[<?php echo $module_row; ?>][city_name]" value="<?php echo $module['city_name']; ?>" size="10" />
										
										<br /><br /><br />
										<a onClick="$('#module-row<?php echo $module_row; ?>').remove();" class="button"><?php echo $button_delete; ?></a>
									</td>
								</tr>								
							</tbody>
							<?php $module_row++; ?>
						<?php } ?>
						<tfoot>
							<tr>
								<td colspan="8"></td>
								<td class="center"><a onClick="addModule();" class="button-insert"><?php echo $button_insert; ?></a></td>
							</tr>
						</tfoot>
					</table>
					<?php echo $entry_tip_text; ?>
				</div>
				<div id="tab-settings">
					<h2><?php echo $text_settings; ?></h2>
					<table class="form">
						<tr>
							<td><?php echo $entry_title; ?></td>
							<td>
								<?php foreach ($languages as $language) { ?>
									<input type="text" name="pickup_advanced_settings[<?php echo $language['language_id']; ?>][title]" value="<?php echo isset($pickup_advanced_settings[$language['language_id']]) ? $pickup_advanced_settings[$language['language_id']]['title'] : ''; ?>" style="margin-bottom: 3px;" />
									<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" style="margin-bottom: -1px;" />
									<br />
								<?php } ?>
							</td>
						</tr>
						<tr>
							<td><?php echo $entry_null_cost_text; ?></td>
							<td>
								<?php foreach ($languages as $language) { ?>
									<input type="text" name="pickup_advanced_settings[<?php echo $language['language_id']; ?>][null_cost]" value="<?php echo isset($pickup_advanced_settings[$language['language_id']]) ? $pickup_advanced_settings[$language['language_id']]['null_cost'] : ''; ?>" style="margin-bottom: 3px;" />
									<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" style="margin-bottom: -1px;" />
									<br />
								<?php } ?>
							</td>
						</tr>
						<tr>
							<td><?php echo $entry_null_cost; ?></td>
							<td>
								<select name="pickup_advanced_null_cost">
									<?php if ($pickup_advanced_null_cost) { ?>
										<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
										<option value="0"><?php echo $text_disabled; ?></option>
										<?php } else { ?>
										<option value="1"><?php echo $text_enabled; ?></option>
										<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
									<?php } ?>
								</select>
							</td>
						</tr>
						<tr style="display: none;">
							<td><?php echo $entry_group_points; ?></td>
							<td>
								<select name="pickup_advanced_group_points">
									<?php if ($pickup_advanced_group_points) { ?>
										<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
										<option value="0"><?php echo $text_disabled; ?></option>
										<?php } else { ?>
										<option value="1"><?php echo $text_enabled; ?></option>
										<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
									<?php } ?>
								</select>
							</td>
						</tr>
						<tr>
							<td><?php echo $entry_status; ?></td>
							<td>
								<select name="pickup_advanced_status">
									<?php if ($pickup_advanced_status) { ?>
										<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
										<option value="0"><?php echo $text_disabled; ?></option>
										<?php } else { ?>
										<option value="1"><?php echo $text_enabled; ?></option>
										<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
									<?php } ?>
								</select>
							</td>
						</tr>
						<tr>
							<td><?php echo $entry_sort_order; ?></td>
							<td><input type="text" name="pickup_advanced_sort_order" value="<?php echo $pickup_advanced_sort_order; ?>" size="1" /></td>
						</tr>
					</table>
				</div>
				<div id="tab-about">
					<h2><?php echo $text_about_title; ?></h2>
					<table class="form">
						<?php echo $text_about_description; ?>
					</table>
				</div>
			</form>
		</div>
	</div>
</div>
<script type="text/javascript">
	<!--
	var module_row = <?php echo $module_row; ?>;
	
	function addModule()
	{
		html  = '<tbody id="module-row' + module_row + '">';
		html += '  <tr>';
		html += '    <td style="width: 130px;">';
		html += '      <div class="image" style="padding: 8px 8px 0px; width: 115px;">';
		html += '        <img src="<?php echo $no_image; ?>" alt="" id="thumb' + module_row + '" />';
		html += '        <input type="hidden" name="pickup_advanced_module[' + module_row + '][image]" value="" id="image' + module_row + '" />';
		html += '        <span style="float: right; margin-top: 8px; margin-left: 8px;">';
		html += '        <a onClick="imageUpload(\'image' + module_row + '\', \'thumb' + module_row + '\');"><?php echo $text_browse; ?></a>';
		html += '        <br />';
		html += '        <a onClick="$(\'#thumb' + module_row + '\').attr(\'src\', \'<?php echo $no_image; ?>\'); $(\'#image' + module_row + '\').attr(\'value\', \'\');"><?php echo $text_clear; ?></a>';
		html += '        </span>';
		html += '      </div>';
		html += '    </td>';
		html += '    <td style="width: 185px;">';
		<?php foreach ($languages as $language) { ?>
			var language_id    = "<?php echo $language['language_id']; ?>";
			var language_image = "<?php echo $language['image']; ?>";
			var language_name  = "<?php echo $language['name']; ?>";
			html += '      <input type="text" name="pickup_advanced_module[' + module_row + '][' + language_id + '][description]" value="" style="margin-bottom: 3px;" size="19" />';
			html += '      <img src="view/image/flags/' + language_image + '" title="' + language_name + '" style="margin-bottom: -1px;" />';
			html += '      <br />';
		<?php } ?>
		html += '    </td>';
		html += '    <td style="width: 185px;">';
		<?php foreach ($languages as $language) { ?>
			var language_id    = "<?php echo $language['language_id']; ?>";
			var language_image = "<?php echo $language['image']; ?>";
			var language_name  = "<?php echo $language['name']; ?>";
			html += '      <input type="text" name="pickup_advanced_module[' + module_row + '][' + language_id + '][link]" value="" style="margin-bottom: 3px;" size="19" />';
			html += '      <img src="view/image/flags/' + language_image + '" title="' + language_name + '" style="margin-bottom: -1px;" />';
			html += '      <br />';
		<?php } ?>
		html += '    </td>';
		html += '    <td style="width: 165px;">';
		<?php foreach ($languages as $language) { ?>
			var language_id    = "<?php echo $language['language_id']; ?>";
			var language_image = "<?php echo $language['image']; ?>";
			var language_name  = "<?php echo $language['name']; ?>";
			html += '      <input type="text" name="pickup_advanced_module[' + module_row + '][' + language_id + '][link_text]" value="" style="margin-bottom: 3px;" size="16" />';
			html += '      <img src="view/image/flags/' + language_image + '" title="' + language_name + '" style="margin-bottom: -1px;" />';
			html += '      <br />';
		<?php } ?>
		html += '    </td>';
		html += '    <td style="width: 85px;" class="center">';
		html += '      <select name="pickup_advanced_module[' + module_row + '][link_status]">';
		html += '        <option value="1"><?php echo $text_enabled; ?></option>';
		html += '        <option value="0" selected="selected"><?php echo $text_disabled; ?></option>';
		html += '      </select>';
		html += '    </td>';
		html += '    <td><input type="text" name="pickup_advanced_module[' + module_row + '][cost]" value="0" size="10" /></td>';
		html += '    <td style="width: 115px;" class="center">';
		html += '      <select name="pickup_advanced_module[' + module_row + '][weight]">';
		html += '        <option value="1"><?php echo $text_enabled; ?></option>';
		html += '        <option value="0" selected="selected"><?php echo $text_disabled; ?></option>';
		html += '      </select>';
		html += '    </td>';
		html += '    <td style="width: 115px;" class="center">';
		html += '      <select name="pickup_advanced_module[' + module_row + '][relation]">';
		html += '        <option value="1"><?php echo $text_enabled; ?></option>';
		html += '        <option value="0" selected="selected"><?php echo $text_disabled; ?></option>';
		html += '      </select>';
		html += '    </td>';
		html += '    <td style="width: 115px;" class="center">';
		html += '      <select name="pickup_advanced_module[' + module_row + '][percentage]">';
		html += '        <option value="1"><?php echo $text_enabled; ?></option>';
		html += '        <option value="0" selected="selected"><?php echo $text_disabled; ?></option>';
		html += '      </select>';
		html += '    </td>';
		html += '    <td><input type="text" name="pickup_advanced_module[' + module_row + '][display_threshold]" value="0" size="7" /></td>';
		html += '    <td style="width: 100px;" class="center">';
		html += '      <select name="pickup_advanced_module[' + module_row + '][geo_zone_id]">';
		html += '        <option value="0" selected="selected"><?php echo $text_all_zones; ?></option>';
		html += '        <?php foreach ($geo_zones as $geo_zone) { ?>';
		html += '        <option value="<?php echo $geo_zone["geo_zone_id"]; ?>"><?php echo $geo_zone["name"]; ?></option>';
		html += '        <?php } ?>';
		html += '      </select>';
		html += '    </td>';
		html += '    <td style="width: 85px;" class="center">';
		html += '      <select name="pickup_advanced_module[' + module_row + '][status]">';
		html += '        <option value="1" selected="selected"><?php echo $text_enabled; ?></option>';
		html += '        <option value="0"><?php echo $text_disabled; ?></option>';
		html += '      </select>';
		html += '    </td>';
		html += '    <td style="width: 75px;" class="center"><input type="text" name="pickup_advanced_module[' + module_row + '][sort_order]" value="' + (module_row + 1) + '" size="1" /></td>';
		html += '    <td style="width: 85px;" class="center"><a onclick="$(\'#module-row' + module_row + '\').remove();" class="button-delete"><?php echo $button_delete; ?></a></td>';
		html += '  </tr>';
		html += '</tbody>';
		
		$('#module tfoot').before(html);
		
		module_row++;
	}
	
	function imageUpload(field, thumb)
	{
		$('#dialog').remove();
		
		$('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?route=common/filemanager&token=<?php echo $token; ?>&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');
		
		$('#dialog').dialog
		({
			title: '<?php echo $text_image_manager; ?>',
			close: function (event, ui) {
				if ($('#' + field).attr('value')) {
					$.ajax({
						url: 'index.php?route=common/filemanager/image&token=<?php echo $token; ?>&image=' + encodeURIComponent($('#' + field).val()),
						dataType: 'text',
						success: function(data) {
							$('#' + thumb).replaceWith('<img src="' + data + '" alt="" id="' + thumb + '" style="width: 47px; height: 47px;" />');
						}
					});
				}
			},
			bgiframe: false,
			width: 800,
			height: 400,
			resizable: false,
			modal: false
		});
	};
	
	$('#tabs a').tabs();
	//-->
</script>
<?php echo $footer; ?>

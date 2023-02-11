<?php echo $header; ?>

<link type="text/css" href="view/stylesheet/module/emailtemplate.css" rel="stylesheet" />
<link type="text/css" href="view/stylesheet/colorpicker.css" rel="stylesheet" />

<div id="content">
	<div class="breadcrumb">
 	<?php foreach ($breadcrumbs as $breadcrumb) { ?>
  		<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
  	<?php } ?>
	</div>

	<?php if ($error_warning) { ?><div class="warning"><?php echo $error_warning; ?></div><?php } ?>
	<?php if ($error_attention) { ?><div class="attention"><?php echo $error_attention; ?></div><?php } ?>
	<?php if ($success) { ?><div class="success"><?php echo $success; ?></div><?php } ?>

	<div class="box" id="emailtemplate">
		<div class="heading">
			<h1><img src="view/image/setting.png" alt="" /><?php
				if($emailtemplate_config_id == 1){
					$heading_config .= ' - <b>' . $text_main . '</b>';
				} elseif($emailtemplate_config['name']) {
					$heading_config .= ' - <b>' . $emailtemplate_config['name'] . '</b>';
				}
		 		echo $heading_config; ?></h1>

			<div class="buttons">
				<a id="submitButton" href="javascript:void(0)" onclick="$('#form').attr('action', '<?php echo $action; ?>'); $('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a>
				<?php if(isset($action_delete)){ ?>
					<a href="javascript:void(0)" onclick="$('#form').attr('action', '<?php echo $action_delete; ?>'); $('#form').submit();" class="button"><span><?php echo $button_delete; ?></span></a>
				<?php } ?>

				<span style="width:1px; height:24px; background:#e2e2e2; border-right:1px solid #fff; border-left:1px solid #fff; display:inline-block; *display:inline; zoom:1; line-height:0; vertical-align:top; margin: 0 1px 0 2px;"></span>

				<a href="javascript:void(0)" onclick="if(confirm('<?php echo $text_create_config_confirm; ?>')){ $('#form').attr('action', '<?php echo $action; ?>&create_custom=1'); $('#form').submit(); }" class="button button-secondary"><span><?php echo $button_create; ?></span></a>
				<a href="<?php echo $cancel; ?>" class="button button-secondary"><span><?php echo $button_back; ?></span></a>
			</div>
		</div>
		<div class="content">
			<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">

				<?php if($emailtemplate_config_id == 1){ ?>
					<input type="hidden" name="language_id" value="0" id="language_id" />
					<input type="hidden" name="store_id" value="0" id="store_id" />
					<input type="hidden" name="customer_group_id" value="0" id="customer_group_id" />
				<?php } ?>

				<table class="form">
					<tr>
						<td>
							<label for="emailtemplate_config_name"><span class="required">*</span> <?php echo $entry_name; ?></label>
						</td>
						<td>
							<input type="text" class="large" name="emailtemplate_config_name" value="<?php echo $emailtemplate_config['name']; ?>" id="emailtemplate_config_name" />
							<?php if (isset($error_emailtemplate_config_name)) {?>
				            	<span class="error"><?php echo $error_emailtemplate_config_name; ?></span>
				            <?php } ?>
						</td>
					</tr>
					<tr>
						<td>
							<label for="emailtemplate_config_style"><?php echo $entry_style; ?></label>
						</td>
						<td>
							<select class="large" name="emailtemplate_config_style" id="emailtemplate_config_style">
								<option value=''><?php echo $text_select; ?></option>
								<option value="page"<?php if ('page' == $emailtemplate_config['style']) { ?> selected="selected"<?php } ?>>Page with shadow</option>
								<option value="white"<?php if ('white' == $emailtemplate_config['style']) { ?> selected="selected"<?php } ?>>Page with white body</option>
								<option value="border"<?php if ('border' == $emailtemplate_config['style']) { ?> selected="selected"<?php } ?>>Page with border</option>
								<option value="clean"<?php if ('clean' == $emailtemplate_config['style']) { ?> selected="selected"<?php } ?>>Clean</option>
								<option value="sections"<?php if ('sections' == $emailtemplate_config['style']) { ?> selected="selected"<?php } ?>>Sections</option>
								<option value="inner_page"<?php if ('inner_page' == $emailtemplate_config['style']) { ?> selected="selected"<?php } ?>>Inner Page</option>
							</select>
							<?php if (isset($error_emailtemplate_config_style)) {?>
				            	<span class="error"><?php echo $error_emailtemplate_config_style; ?></span>
				            <?php } ?>
						</td>
					</tr>
					<?php if(!empty($themes)){ ?>
					<tr>
						<td>
							<label for="emailtemplate_config_theme"><?php echo $entry_theme; ?></label>
						</td>
						<td>
							<select class="large" name="emailtemplate_config_theme" id="emailtemplate_config_theme">
								<?php foreach($themes as $theme){ ?>
								<option value="<?php echo $theme; ?>"<?php if ($theme == $emailtemplate_config['theme']) { ?> selected="selected"<?php } ?>><?php echo $theme; ?></option>
								<?php } ?>
							</select>
							<?php if (isset($error_emailtemplate_config_theme)) {?>
				            	<span class="error"><?php echo $error_emailtemplate_config_theme; ?></span>
				            <?php } ?>
						</td>
					</tr>
					<?php } ?>
				</table>

				<hr />
				<?php if($emailtemplate_config_id != 1){ ?>
    				<h2 style="margin-top:0; padding-top:0"><?php echo $text_options; ?></h2>
    				<p><?php echo $text_config_info; ?></p>

					<table class="form" style="table-layout:fixed">
    				<?php if(!empty($languages)): ?>
    				<tr>
						<td style="text-align: right; max-width: 300px">
							<label for="language_id"><?php echo $entry_language; ?></label>
						</td>
						<td>
							<select class="large" name="language_id" id="language_id">
								<option value="-1"><?php echo $text_select; ?></option>
								<?php foreach($languages as $language){ ?>
								<option value="<?php echo $language['language_id']; ?>"<?php if ($language['language_id'] === $emailtemplate_config['language_id']) { ?> selected="selected"<?php } ?>>
									<?php echo $language['name']; ?>
								</option>
								<?php } ?>
							</select>
						</td>
					</tr>
					<?php endif; ?>

    				<?php if(!empty($stores)): ?>
    				<tr>
						<td style="text-align: right; max-width: 300px">
							<label for="store_id"><?php echo $entry_store; ?></label>
						</td>
						<td>
							<select class="large" name="store_id" id="store_id">
								<option value="-1"><?php echo $text_select; ?></option>
								<?php foreach($stores as $store){ ?>
								<option value="<?php echo $store['store_id']; ?>"<?php if ($store['store_id'] == $emailtemplate_config['store_id']) { ?> selected="selected"<?php } ?>>
									<?php echo $store['store_name']; ?>
								</option>
								<?php } ?>
							</select>
						</td>
					</tr>
					<?php endif; ?>

    				<?php if(!empty($customer_groups)): ?>
    				<tr>
						<td style="text-align: right; max-width: 300px">
							<label for="customer_group_id"><?php echo $entry_customer_group; ?></label>
						</td>
						<td>
							<select class="large" name="customer_group_id" id="customer_group_id">
								<option value="-1"><?php echo $text_select; ?></option>
								<?php foreach($customer_groups as $customer_group){ ?>
								<option value="<?php echo $customer_group['customer_group_id']; ?>"<?php if ($customer_group['customer_group_id'] == $emailtemplate_config['customer_group_id']) { ?> selected="selected"<?php } ?>>
									<?php echo $customer_group['name']; ?>
								</option>
								<?php } ?>
							</select>
						</td>
					</tr>
					<?php endif; ?>
				</table>
				<hr />
				<?php } ?>

	    		<div class="tabsHolder">
	    			<div id="editor" class="vtabs tab-nav-editor">
	    				<a href="#editor-settings"><?php echo $heading_settings; ?></a>
	    				<?php if($emailtemplate_config['style'] == 'sections'){ ?>
	    					<a href="#editor-sections"><?php echo $heading_sections; ?></a>
    					<?php } ?>
	    				<a href="#editor-content"><?php echo $heading_content; ?></a>
	    				<a href="#editor-header"><?php echo $heading_header; ?></a>
	    				<a href="#editor-footer"><?php echo $heading_footer; ?></a>
	    				<a href="#editor-shadow"><?php echo $heading_shadow; ?></a>
	    				<a href="#editor-showcase"><?php echo $heading_showcase; ?></a>
	    				<a href="#editor-tracking"><?php echo $heading_tracking; ?></a>
	    			</div>

	    			<div id="editor-settings" class="vtabs-content tab-content-editor">
	    				<table class="form">
	    					<tr>
								<td>
									<label for="emailtemplate_config_email_width"><?php echo $entry_email_width; ?></label>
								</td>
								<td>
									<input type="text" name="emailtemplate_config_email_width" value="<?php echo $emailtemplate_config['email_width']; ?>" id="emailtemplate_config_email_width" />px
								</td>
							</tr>
							<tr>
								<td>
									<label><?php echo $entry_responsive; ?></label>
								</td>
								<td>
									<label class="radio"><input type="radio" name="emailtemplate_config_email_responsive" value="1" id="emailtemplate_config_email_responsive" <?php echo ($emailtemplate_config['email_responsive'] == 1) ? ' checked="checked"' : ''; ?>/> <?php echo $text_yes; ?></label>
									<label class="radio"><input type="radio" name="emailtemplate_config_email_responsive" value="0" id="emailtemplate_config_email_responsive" <?php echo ($emailtemplate_config['email_responsive'] == 0) ? ' checked="checked"' : ''; ?>/> <?php echo $text_no; ?></label>
									<?php if(isset($error_emailtemplate_config_email_responsive)) { ?><span class="error"><?php echo $error_emailtemplate_config_email_responsive; ?></span><?php } ?>
								</td>
							</tr>
							<tr>
								<td>
									<label for="emailtemplate_config_log"><?php echo $entry_log; ?></label>
								</td>
								<td>
									<label class="radio">
										<input type="radio" name="emailtemplate_config_log" value="1" id="emailtemplate_config_log" <?php echo ($emailtemplate_config['log'] == 1) ? ' checked="checked"' : ''; ?>/>
										<?php echo $text_yes; ?>
									</label>
									<label class="radio">
										<input type="radio" name="emailtemplate_config_log" value="0" id="emailtemplate_config_log" <?php echo ($emailtemplate_config['log'] == 0) ? ' checked="checked"' : ''; ?>/>
										<?php echo $text_no; ?>
									</label>
									<?php if(isset($error_emailtemplate_config_log)) { ?><span class="error"><?php echo $error_emailtemplate_config_log; ?></span><?php } ?>
								</td>
							</tr>
							<tr>
								<td>
									<label for="emailtemplate_config_log_read"><?php echo $entry_log_read; ?></label>
								</td>
								<td>
									<?php echo $entry_log_read_info; ?>
									<label class="radio">
										<input type="radio" name="emailtemplate_config_log_read" value="1" id="emailtemplate_config_log_read" <?php echo ($emailtemplate_config['log_read'] == 1) ? ' checked="checked"' : ''; ?>/>
										<?php echo $text_yes; ?>
									</label>
									<label class="radio">
										<input type="radio" name="emailtemplate_config_log_read" value="0" id="emailtemplate_config_log_read" <?php echo ($emailtemplate_config['log_read'] == 0) ? ' checked="checked"' : ''; ?>/>
										<?php echo $text_no; ?>
									</label>
									<?php if(isset($error_emailtemplate_config_log_read)) { ?><span class="error"><?php echo $error_emailtemplate_config_log_read; ?></span><?php } ?>
								</td>
							</tr>
							<tr>
								<td>
									<label for="emailtemplate_config_wrapper_tpl"><?php echo $entry_template_wrapper; ?></label>
								</td>
								<td>
									<input type="text" name="emailtemplate_config_wrapper_tpl" value="<?php echo $emailtemplate_config['wrapper_tpl']; ?>" id="emailtemplate_config_wrapper_tpl" />
								</td>
							</tr>
							<tr>
								<td>
									<label for="emailtemplate_config_body_bg_color"><?php echo $entry_body_bg_color; ?></label>
								</td>
								<td>
									<input type="text" class="fieldColorPicker" name="emailtemplate_config_body_bg_color" value="<?php echo $emailtemplate_config['body_bg_color']; ?>" id="emailtemplate_config_body_bg_color" />
									<span style="background-color:<?php echo $emailtemplate_config['body_bg_color']; ?>;" class="colorPickerPreview">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
								</td>
							</tr>
							<tr>
								<td>
									<label for="emailtemplate_config_page_bg_color"><?php echo $entry_page_bg_color; ?></label>
								</td>
								<td>
									<input type="text" class="fieldColorPicker" name="emailtemplate_config_page_bg_color" value="<?php echo $emailtemplate_config['page_bg_color']; ?>" id="emailtemplate_config_page_bg_color" />
									<span style="background-color:<?php echo $emailtemplate_config['page_bg_color']; ?>;" class="colorPickerPreview">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
								</td>
							</tr>
							<tr>
								<td>
									<label for="emailtemplate_config_body_font_color"><?php echo $entry_body_font_color; ?></label>
								</td>
								<td>
									<input type="text" class="fieldColorPicker" name="emailtemplate_config_body_font_color" value="<?php echo $emailtemplate_config['body_font_color']; ?>" id="emailtemplate_config_body_font_color" />
									<span style="background-color:<?php echo $emailtemplate_config['body_font_color']; ?>;" class="colorPickerPreview">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
								</td>
							</tr>
							<tr>
								<td>
									<label for="emailtemplate_config_body_link_color"><?php echo $entry_body_link_color; ?></label>
								</td>
								<td>
									<input type="text" class="fieldColorPicker" name="emailtemplate_config_body_link_color" value="<?php echo $emailtemplate_config['body_link_color']; ?>" id="emailtemplate_config_body_link_color" />
									<span style="background-color:<?php echo $emailtemplate_config['body_link_color']; ?>;" class="colorPickerPreview">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
								</td>
							</tr>
							<tr>
								<td>
									<label for="emailtemplate_config_body_heading_color"><?php echo $entry_body_heading_color; ?></label>
								</td>
								<td>
									<input type="text" class="fieldColorPicker" name="emailtemplate_config_body_heading_color" value="<?php echo $emailtemplate_config['body_heading_color']; ?>" id="emailtemplate_config_body_heading_color" />
									<span style="background-color:<?php echo $emailtemplate_config['body_heading_color']; ?>;" class="colorPickerPreview">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
								</td>
							</tr>
							<tr>
								<td>
									<label for="emailtemplate_config_text_align"><?php echo $entry_text_align; ?></label>
								</td>
								<td>
									<select name="emailtemplate_config_text_align" id="emailtemplate_config_text_align">
										<option value="left"<?php if($emailtemplate_config['text_align'] == 'left'){ ?> selected="selected"<?php } ?>><?php echo $text_left; ?></option>
										<option value="right"<?php if($emailtemplate_config['text_align'] == 'right'){ ?> selected="selected"<?php } ?>><?php echo $text_right; ?></option>
									</select>
								</td>
							</tr>
							<tr>
								<td>
									<label for="emailtemplate_config_paget_align"><?php echo $entry_page_align; ?></label>
								</td>
								<td>
									<select name="emailtemplate_config_page_align" id="emailtemplate_config_page_align">
										<option value="center"<?php if($emailtemplate_config['page_align'] == 'center'){ ?> selected="selected"<?php } ?>><?php echo $text_center; ?></option>
										<option value="left"<?php if($emailtemplate_config['page_align'] == 'left'){ ?> selected="selected"<?php } ?>><?php echo $text_left; ?></option>
										<option value="right"<?php if($emailtemplate_config['page_align'] == 'right'){ ?> selected="selected"<?php } ?>><?php echo $text_right; ?></option>
									</select>
								</td>
							</tr>
							<tr>
								<td>
									<label for="emailtemplate_config_customer_register_validate_email"><?php echo $entry_customer_register_validate_email; ?></label>
								</td>
								<td>
									<label class="radio">
										<input type="radio" name="emailtemplate_config_customer_register_validate_email" value="1" id="emailtemplate_config_customer_register_validate_email" <?php echo ($emailtemplate_config['customer_register_validate_email'] == 1) ? ' checked="checked"' : ''; ?>/>
										<?php echo $text_yes; ?>
									</label>
									<label class="radio">
										<input type="radio" name="emailtemplate_config_customer_register_validate_email" value="0" id="emailtemplate_config_customer_register_validate_email" <?php echo ($emailtemplate_config['customer_register_validate_email'] == 0) ? ' checked="checked"' : ''; ?>/>
										<?php echo $text_no; ?>
									</label>
									<?php if(isset($error_emailtemplate_config_customer_register_validate_email)) { ?><span class="error"><?php echo $error_emailtemplate_config_customer_register_validate_email; ?></span><?php } ?>
								</td>
							</tr>
							<tr>
								<td>
									<label for="emailtemplate_config_table_quantity"><?php echo $entry_table_quantity; ?></label>
								</td>
								<td>
									<label class="radio">
										<input type="radio" name="emailtemplate_config_table_quantity" value="1" id="emailtemplate_config_table_quantity" <?php echo ($emailtemplate_config['table_quantity'] == 1) ? ' checked="checked"' : ''; ?>/>
										<?php echo $text_yes; ?>
									</label>
									<label class="radio">
										<input type="radio" name="emailtemplate_config_table_quantity" value="0" id="emailtemplate_config_table_quantity" <?php echo ($emailtemplate_config['table_quantity'] == 0) ? ' checked="checked"' : ''; ?>/>
										<?php echo $text_no; ?>
									</label>
									<?php if(isset($error_emailtemplate_config_table_quantity)) { ?><span class="error"><?php echo $error_emailtemplate_config_table_quantity; ?></span><?php } ?>
								</td>
							</tr>
	    				</table>
	    			</div><!-- editor-settings -->

	    			<?php if($emailtemplate_config['style'] == 'sections'){ ?>
	    			<div id="editor-sections" class="vtabs-content">
	    				<table class="form">
	    					<tr>
								<td>
									<label for="emailtemplate_config_head_section_bg_color"><?php echo $text_head; ?></label>
								</td>
								<td>
									<input type="text" class="fieldColorPicker" name="emailtemplate_config_head_section_bg_color" value="<?php echo $emailtemplate_config['head_section_bg_color']; ?>" id="emailtemplate_config_head_section_bg_color" />
									<span style="background-color:<?php echo $emailtemplate_config['head_section_bg_color']; ?>;" class="colorPickerPreview">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
								</td>
							</tr>
							<tr>
								<td>
									<label for="emailtemplate_config_header_section_bg_color"><?php echo $text_header; ?></label>
								</td>
								<td>
									<input type="text" class="fieldColorPicker" name="emailtemplate_config_header_section_bg_color" value="<?php echo $emailtemplate_config['header_section_bg_color']; ?>" id="emailtemplate_config_header_section_bg_color" />
									<span style="background-color:<?php echo $emailtemplate_config['header_section_bg_color']; ?>;" class="colorPickerPreview">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
								</td>
							</tr>
							<tr>
								<td>
									<label for="emailtemplate_config_body_section_bg_color"><?php echo $text_body; ?></label>
								</td>
								<td>
									<input type="text" class="fieldColorPicker" name="emailtemplate_config_body_section_bg_color" value="<?php echo $emailtemplate_config['body_section_bg_color']; ?>" id="emailtemplate_config_body_section_bg_color" />
									<span style="background-color:<?php echo $emailtemplate_config['body_section_bg_color']; ?>;" class="colorPickerPreview">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
								</td>
							</tr>
							<tr>
								<td>
									<label for="emailtemplate_config_showcase_section_bg_color"><?php echo $entry_showcase; ?></label>
								</td>
								<td>
									<input type="text" class="fieldColorPicker" name="emailtemplate_config_showcase_section_bg_color" value="<?php echo $emailtemplate_config['showcase_section_bg_color']; ?>" id="emailtemplate_config_showcase_section_bg_color" />
									<span style="background-color:<?php echo $emailtemplate_config['showcase_section_bg_color']; ?>;" class="colorPickerPreview">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
								</td>
							</tr>
							<tr>
								<td>
									<label for="emailtemplate_config_footer_section_bg_color"><?php echo $text_footer; ?></label>
								</td>
								<td>
									<input type="text" class="fieldColorPicker" name="emailtemplate_config_footer_section_bg_color" value="<?php echo $emailtemplate_config['footer_section_bg_color']; ?>" id="emailtemplate_config_footer_section_bg_color" />
									<span style="background-color:<?php echo $emailtemplate_config['footer_section_bg_color']; ?>;" class="colorPickerPreview">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
								</td>
							</tr>
						</table>
					</div>
					<?php } ?>

	    			<div id="editor-content" class="vtabs-content">
	    				<table class="form">
	    					<tr>
								<td>
									<label><?php echo $entry_head_text; ?></label>
								</td>
								<td>
									<textarea name="emailtemplate_config_head_text" id="emailtemplate_config_head_text"><?php echo $emailtemplate_config['head_text']; ?></textarea>
								</td>
							</tr>
	    					<tr>
								<td>
									<label><?php echo $entry_view_browser_text; ?></label>
								</td>
								<td>
									<textarea name="emailtemplate_config_view_browser_text" id="emailtemplate_config_view_browser_text"><?php echo $emailtemplate_config['view_browser_text']; ?></textarea>
								</td>
							</tr>
							<tr>
								<td>
									<label><?php echo $entry_page_footer_text; ?></label>
								</td>
								<td>
									<textarea name="emailtemplate_config_page_footer_text" id="emailtemplate_config_page_footer_text"><?php echo $emailtemplate_config['page_footer_text']; ?></textarea>
								</td>
							</tr>
							<tr>
	    						<td>
									<label><?php echo $entry_footer_text; ?></label>
								</td>
								<td>
									<textarea name="emailtemplate_config_footer_text" id="emailtemplate_config_footer_text"><?php echo $emailtemplate_config['footer_text']; ?></textarea>
								</td>
							</tr>
	    				</table>
    				</div>

	    			<div id="editor-header" class="vtabs-content tab-content-editor">
	    				<table class="form">
							<tr>
								<td>
									<label for="emailtemplate_config_header_height"><?php echo $entry_height; ?></label>
								</td>
								<td>
									<input type="text" name="emailtemplate_config_header_height" value="<?php echo $emailtemplate_config['header_height']; ?>" id="emailtemplate_config_header_height" /> px
								</td>
							</tr>
							<tr>
								<td>
									<label for="emailtemplate_config_header_bg_color"><?php echo $entry_header_bg_color; ?></label>
								</td>
								<td>
									<input type="text" class="fieldColorPicker" name="emailtemplate_config_header_bg_color" value="<?php echo $emailtemplate_config['header_bg_color']; ?>" id="emailtemplate_config_header_bg_color" />
									<span style="background-color:<?php echo $emailtemplate_config['header_bg_color']; ?>;" class="colorPickerPreview">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
								</td>
							</tr>
							<tr>
								<td>
									<label for="emailtemplate_config_header_border_color"><?php echo $entry_header_border_color; ?></label>
								</td>
								<td>
									<input type="text" class="fieldColorPicker" name="emailtemplate_config_header_border_color" value="<?php echo $emailtemplate_config['header_border_color']; ?>" id="emailtemplate_config_header_border_color" />
									<span style="background-color:<?php echo $emailtemplate_config['header_border_color']; ?>;" class="colorPickerPreview">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
								</td>
							</tr>
							<tr>
								<td>
									<label for="emailtemplate_config_logo_align"><?php echo $text_align; ?></label>
								</td>
								<td>
									<select name="emailtemplate_config_logo_align" id="emailtemplate_config_logo_align" class="small">
										<option value="left"<?php if($emailtemplate_config['logo_align'] == 'left'){ ?> selected="selected"<?php } ?>><?php echo $text_left; ?></option>
										<option value="right"<?php if($emailtemplate_config['logo_align'] == 'right'){ ?> selected="selected"<?php } ?>><?php echo $text_right; ?></option>
										<option value="center"<?php if($emailtemplate_config['logo_align'] == 'center'){ ?> selected="selected"<?php } ?>><?php echo $text_center; ?></option>
									</select>

									/

									<select name="emailtemplate_config_logo_valign" id="emailtemplate_config_logo_valign" class="small">
										<option value="top"<?php if($emailtemplate_config['logo_valign'] == 'top'){ ?> selected="selected"<?php } ?>><?php echo $text_top; ?></option>
										<option value="middle"<?php if($emailtemplate_config['logo_valign'] == 'middle'){ ?> selected="selected"<?php } ?>><?php echo $text_middle; ?></option>
										<option value="bottom"<?php if($emailtemplate_config['logo_valign'] == 'bottom'){ ?> selected="selected"<?php } ?>><?php echo $text_bottom; ?></option>
										<option value="baseline"<?php if($emailtemplate_config['logo_valign'] == 'baseline'){ ?> selected="selected"<?php } ?>><?php echo $text_baseline; ?></option>
									</select>
								</td>
							</tr>
							<tr>
								<td>
									<label for="emailtemplate_config_logo_font_size"><?php echo $text_font_size; ?></label>
								</td>
								<td>
									<input type="text" name="emailtemplate_config_logo_font_size" value="<?php echo $emailtemplate_config['logo_font_size']; ?>" id="emailtemplate_config_logo_font_size" />
								</td>
							</tr>
							<tr>
								<td>
									<label for="emailtemplate_config_logo_font_color"><?php echo $text_text_color; ?></label>
								</td>
								<td>
									<input type="text" class="fieldColorPicker" name="emailtemplate_config_logo_font_color" value="<?php echo $emailtemplate_config['logo_font_color']; ?>" id="emailtemplate_config_logo_font_color" />
									<span style="background-color:<?php echo $emailtemplate_config['logo_font_color']; ?>;" class="colorPickerPreview">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
								</td>
							</tr>
						</table>
						<hr />

	    				<table class="form">
							<tr>
								<td>
									<label><?php echo $entry_logo; ?></label>
								</td>
								<td>
									<div class="image">
										<img src="<?php echo $emailtemplate_config['logo_thumb']; ?>" alt="" id="thumb-logo" />
										<input type="hidden" name="emailtemplate_config_logo" value="<?php echo $emailtemplate_config['logo']; ?>" id="image-logo"  />
	                  					<br />
	                  					<a onclick="image_upload('image-logo', 'thumb-logo');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;
	                  					<a onclick="$('#thumb-logo').attr('src', '<?php echo $no_image; ?>'); $('#image-logo').attr('value', '');"><?php echo $text_clear; ?></a>
	                  				</div>
	                  				<?php if (isset($error_emailtemplate_config_logo)) {?>
						            	<span class="error"><?php echo $error_emailtemplate_config_logo; ?></span>
						            <?php } ?>
								</td>
							</tr>
							<tr>
								<td>
									<label for="emailtemplate_config_logo_width"><span class="required">*</span> <?php echo $entry_logo_resize_options; ?></label>
								</td>
								<td>
									<b>w: </b><input class="small" title="<?php echo $text_width; ?>" type="text" name="emailtemplate_config_logo_width" value="<?php echo $emailtemplate_config['logo_width']; ?>" id="emailtemplate_config_logo_width" /> px &nbsp;&nbsp;
									<b>h: </b><input class="small" title="<?php echo $text_height; ?>" type="text" name="emailtemplate_config_logo_height" value="<?php echo $emailtemplate_config['logo_height']; ?>" id="emailtemplate_config_logo_height" /> px
									<?php if (isset($error_emailtemplate_config_logo_width) || isset($error_emailtemplate_config_logo_height)): ?>
						            	<span class="error"><?php echo $error_required; ?></span>
						            <?php endif; ?>
								</td>
							</tr>
							<tr>
								<td>
									<label><?php echo $entry_header_bg_image; ?></label>
								</td>
								<td>
									<div class="image">
										<img src="<?php echo $emailtemplate_config['header_bg_image_thumb']; ?>" alt="" id="thumb-header-bg" />
										<input type="hidden" name="emailtemplate_config_header_bg_image" value="<?php echo $emailtemplate_config['header_bg_image']; ?>" id="image-header-bg"  />
	                  					<br />
	                  					<a onclick="image_upload('image-header-bg', 'thumb-header-bg');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;
	                  					<a onclick="$('#thumb-header-bg').attr('src', '<?php echo $no_image; ?>'); $('#image-header-bg').attr('value', '');"><?php echo $text_clear; ?></a>
	                  				</div>
								</td>
							</tr>
						</table>
	    			</div><!-- editor-logo -->

	    			<div id="editor-footer" class="vtabs-content tab-content-editor">
	    				<table class="form">
							<tr>
								<td>
									<label for="emailtemplate_config_footer_height"><?php echo $text_height; ?></label>
								</td>
								<td>
									<input type="text" name="emailtemplate_config_footer_height" value="<?php echo $emailtemplate_config['footer_height']; ?>" id="emailtemplate_config_footer_height" /> px
								</td>
							</tr>
							<tr>
							<tr>
								<td>
									<label for="emailtemplate_config_footer_font_color"><?php echo $text_text_color; ?></label>
								</td>
								<td>
									<input type="text" class="fieldColorPicker" name="emailtemplate_config_footer_font_color" value="<?php echo $emailtemplate_config['footer_font_color']; ?>" id="emailtemplate_config_footer_font_color" />
									<span style="background-color:<?php echo $emailtemplate_config['footer_font_color']; ?>;" class="colorPickerPreview">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
								</td>
							</tr>
							<tr>
								<td>
									<label for="emailtemplate_config_footer_align"><?php echo $text_align; ?></label>
								</td>
								<td>
									<select name="emailtemplate_config_footer_align" id="emailtemplate_config_footer_align" class="small">
										<option value="center"<?php if($emailtemplate_config['footer_align'] == 'center'){ ?> selected="selected"<?php } ?>><?php echo $text_center; ?></option>
										<option value="left"<?php if($emailtemplate_config['footer_align'] == 'left'){ ?> selected="selected"<?php } ?>><?php echo $text_left; ?></option>
										<option value="right"<?php if($emailtemplate_config['footer_align'] == 'right'){ ?> selected="selected"<?php } ?>><?php echo $text_right; ?></option>
									</select>
									/
									<select name="emailtemplate_config_footer_valign" id="emailtemplate_config_footer_valign" class="small">
										<option value="top"<?php if($emailtemplate_config['footer_valign'] == 'top'){ ?> selected="selected"<?php } ?>><?php echo $text_top; ?></option>
										<option value="middle"<?php if($emailtemplate_config['footer_valign'] == 'middle'){ ?> selected="selected"<?php } ?>><?php echo $text_middle; ?></option>
										<option value="bottom"<?php if($emailtemplate_config['footer_valign'] == 'bottom'){ ?> selected="selected"<?php } ?>><?php echo $text_bottom; ?></option>
										<option value="baseline"<?php if($emailtemplate_config['footer_valign'] == 'baseline'){ ?> selected="selected"<?php } ?>><?php echo $text_baseline; ?></option>
									</select>
								</td>
							</tr>
						</table>
					</div>

	    			<div id="editor-shadow" class="vtabs-content tab-content-editor">
	    				<?php echo $text_shadow_info; ?>

	    				<table class="form">
	    					<tr>
								<td>
									<b><?php echo $text_top; ?></b>
								</td>
								<td>
									<table class="form-vertical" cellpadding="5">
				    					<tr>
				    						<td>
												<label for="emailtemplate_config_shadow_top_length">
													<?php echo $text_height; ?>
												</label>
											</td>
											<td>
				    							<input class="small" type="text" name="emailtemplate_config_shadow_top[length]" value="<?php echo isset($emailtemplate_config['shadow_top']['length']) ? $emailtemplate_config['shadow_top']['length'] : ''; ?>" id="emailtemplate_config_shadow_top_length" /> px
				    						</td>
											<td>
												<label for="emailtemplate_config_shadow_top_overlap">
													<?php echo $entry_overlap; ?>
												</label>
											</td>
				    						<td>
				    							<input class="small" type="text" name="emailtemplate_config_shadow_top[overlap]" value="<?php echo isset($emailtemplate_config['shadow_top']['overlap']) ? $emailtemplate_config['shadow_top']['overlap'] : ''; ?>" id="emailtemplate_config_shadow_top_overlap" />
				    						</td>
											<td>
												<label for="emailtemplate_config_shadow_top_start">
													<?php echo $text_start; ?>
												</label>
											</td>
											<td>
												<input class="small fieldColorPicker" type="text" name="emailtemplate_config_shadow_top[start]" value="<?php echo isset($emailtemplate_config['shadow_top']['start']) ? $emailtemplate_config['shadow_top']['start'] : ''; ?>" id="emailtemplate_config_shadow_top_start" />
												<span style="background-color:<?php echo isset($emailtemplate_config['shadow_top']['start']) ? $emailtemplate_config['shadow_top']['start'] : ''; ?>;" class="colorPickerPreview">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
											</td>
											<td>
												<label for="emailtemplate_config_shadow_top_end">
													<?php echo $text_end; ?>
												</label>
											</td>
											<td>
												<input class="small fieldColorPicker" type="text" name="emailtemplate_config_shadow_top[end]" value="<?php echo isset($emailtemplate_config['shadow_top']['end']) ? $emailtemplate_config['shadow_top']['end'] : ''; ?>" id="emailtemplate_config_shadow_top_end" />
												<span style="background-color:<?php echo isset($emailtemplate_config['shadow_top']['end']) ? $emailtemplate_config['shadow_top']['end'] : ''; ?>;" class="colorPickerPreview">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<?php if($emailtemplate_config['shadow_bottom']){ ?>
	    					<tr>
								<td>
									<b><?php echo $text_bottom; ?></b>
								</td>
								<td>
									<table class="form-vertical" cellpadding="5">
				    					<tr>
				    						<td>
												<label for="emailtemplate_config_shadow_bottom_length">
													<?php echo $text_height; ?>
												</label>
											</td>
											<td>
				    							<input class="small" type="text" name="emailtemplate_config_shadow_bottom[length]" value="<?php echo $emailtemplate_config['shadow_bottom']['length']; ?>" id="emailtemplate_config_shadow_bottom_length" /> px
				    						</td>
				    						<td>
												<label for="emailtemplate_config_shadow_bottom_overlap">
													<?php echo $entry_overlap; ?>
												</label>
											</td>
				    						<td>
				    							<input class="small" type="text" name="emailtemplate_config_shadow_bottom[overlap]" value="<?php echo $emailtemplate_config['shadow_bottom']['overlap']; ?>" id="emailtemplate_config_shadow_bottom_overlap" />
				    						</td>
											<td>
												<label for="emailtemplate_config_shadow_bottom_start">
													<?php echo $text_start; ?>
												</label>
											</td>
											<td>
												<input class="small fieldColorPicker" type="text" name="emailtemplate_config_shadow_bottom[start]" value="<?php echo $emailtemplate_config['shadow_bottom']['start']; ?>" id="emailtemplate_config_shadow_bottom_start" />
												<span style="background-color:<?php echo $emailtemplate_config['shadow_bottom']['start']; ?>;" class="colorPickerPreview">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
											</td>
											<td>
												<label for="emailtemplate_config_shadow_bottom_end">
													<?php echo $text_end; ?>
												</label>
											</td>
											<td>
												<input class="small fieldColorPicker" type="text" name="emailtemplate_config_shadow_bottom[end]" value="<?php echo $emailtemplate_config['shadow_bottom']['end']; ?>" id="emailtemplate_config_shadow_bottom_end" />
												<span style="background-color:<?php echo $emailtemplate_config['shadow_bottom']['end']; ?>;" class="colorPickerPreview">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<?php } ?>
	    					<tr>
								<td>
									<b><?php echo $text_left; ?></b>
								</td>
								<td>
									<table class="form-vertical" cellpadding="5">
				    					<tr>
				    						<td>
												<label for="emailtemplate_config_shadow_left_length">
													<?php echo $text_width; ?>&nbsp;&nbsp;
												</label>
											</td>
											<td>
				    							<input class="small" type="text" name="emailtemplate_config_shadow_left[length]" value="<?php echo $emailtemplate_config['shadow_left']['length']; ?>" id="emailtemplate_config_shadow_left_length" /> px
				    						</td>
				    						<td>
												<label for="emailtemplate_config_shadow_left_overlap">
													<?php echo $entry_overlap; ?>
												</label>
											</td>
				    						<td>
				    							<input class="small" type="text" name="emailtemplate_config_shadow_left[overlap]" value="<?php echo $emailtemplate_config['shadow_left']['overlap']; ?>" id="emailtemplate_config_shadow_left_overlap" />
				    						</td>
											<td>
												<label for="emailtemplate_config_shadow_left_start">
													<?php echo $text_start; ?>
												</label>
											</td>
											<td>
												<input class="small fieldColorPicker" type="text" name="emailtemplate_config_shadow_left[start]" value="<?php echo $emailtemplate_config['shadow_left']['start']; ?>" id="emailtemplate_config_shadow_left_start" />
												<span style="background-color:<?php echo $emailtemplate_config['shadow_left']['start']; ?>;" class="colorPickerPreview">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
											</td>
											<td>
												<label for="emailtemplate_config_shadow_left_end">
													<?php echo $text_end; ?>
												</label>
											</td>
											<td>
												<input class="small fieldColorPicker" type="text" name="emailtemplate_config_shadow_left[end]" value="<?php echo $emailtemplate_config['shadow_left']['end']; ?>" id="emailtemplate_config_shadow_left_end" />
												<span style="background-color:<?php echo $emailtemplate_config['shadow_left']['end']; ?>;" class="colorPickerPreview">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
											</td>
										</tr>
									</table>
								</td>
							</tr>
	    					<tr>
								<td>
									<b><?php echo $text_right; ?></b>
								</td>
								<td>
									<table class="form-vertical" cellpadding="5">
				    					<tr>
				    						<td>
												<label for="emailtemplate_config_shadow_right_start">
													<?php echo $text_width; ?>&nbsp;&nbsp;
												</label>
											</td>
											<td>
				    							<input class="small" type="text" name="emailtemplate_config_shadow_right[length]" value="<?php echo $emailtemplate_config['shadow_right']['length']; ?>" id="emailtemplate_config_shadow_right_length" /> px
				    						</td>
				    						<td>
												<label for="emailtemplate_config_shadow_right_overlap">
													<?php echo $entry_overlap; ?>
												</label>
											</td>
				    						<td>
				    							<input class="small" type="text" name="emailtemplate_config_shadow_right[overlap]" value="<?php echo $emailtemplate_config['shadow_right']['overlap']; ?>" id="emailtemplate_config_shadow_right_overlap" />
				    						</td>
											<td>
												<label for="emailtemplate_config_shadow_right_start">
													<?php echo $text_start; ?>
												</label>
											</td>
											<td>
												<input class="small fieldColorPicker" type="text" name="emailtemplate_config_shadow_right[start]" value="<?php echo $emailtemplate_config['shadow_right']['start']; ?>" id="emailtemplate_config_shadow_right_start" />
												<span style="background-color:<?php echo $emailtemplate_config['shadow_right']['start']; ?>;" class="colorPickerPreview">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
											</td>
											<td>
												<label for="emailtemplate_config_shadow_right_end">
													<?php echo $text_end; ?>
												</label>
											</td>
											<td>
												<input class="small fieldColorPicker" type="text" name="emailtemplate_config_shadow_right[end]" value="<?php echo $emailtemplate_config['shadow_right']['end']; ?>" id="emailtemplate_config_shadow_right_end" />
												<span style="background-color:<?php echo $emailtemplate_config['shadow_right']['end']; ?>;" class="colorPickerPreview">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td><b><?php echo $entry_corner_image; ?></b></td>
								<td>
									<table class="form-vertical">
				    					<tr>
											<td>
												<div class="image">
													<label for="image-shadow-top-left">
														<?php echo $text_top_left; ?>
													</label> <br /><br />
													<img src="<?php echo $emailtemplate_config['shadow_top']['left_thumb']; ?>" alt="" id="thumb-shadow-top-left" onclick="image_upload('image-shadow-top-left', 'thumb-shadow-top-left');" />
													<input type="hidden" name="emailtemplate_config_shadow_top[left_img]" value="<?php echo $emailtemplate_config['shadow_top']['left_img']; ?>" id="image-shadow-top-left"  />
													<br />
				                  					<a onclick="image_upload('image-shadow-top-left', 'thumb-shadow-top-left');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;
				                  					<a onclick="$('#thumb-shadow-top-left').attr('src', '<?php echo $no_image; ?>'); $('#image-shadow-top-left').attr('value', '');"><?php echo $text_clear; ?></a>
				                  				</div>
											</td>
											<td>
												<div class="image">
													<label for="image-shadow-top-right">
														<?php echo $text_top_right; ?>
													</label> <br /><br />
													<img src="<?php echo $emailtemplate_config['shadow_top']['right_thumb']; ?>" alt="" id="thumb-shadow-top-right" onclick="image_upload('image-shadow-top-right', 'thumb-shadow-top-right');" />
													<input type="hidden" name="emailtemplate_config_shadow_top[right_img]" value="<?php echo $emailtemplate_config['shadow_top']['right_img']; ?>" id="image-shadow-top-right"  />
													<br />
				                  					<a onclick="image_upload('image-shadow-top-right', 'thumb-shadow-top-right');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;
				                  					<a onclick="$('#thumb-shadow-top-right').attr('src', '<?php echo $no_image; ?>'); $('#image-shadow-top-right').attr('value', '');"><?php echo $text_clear; ?></a>
				                  				</div>
											</td>
										</tr>
				    					<tr>
											<td>
												<div class="image">
													<label for="image-shadow-bottom-left">
														<?php echo $text_bottom_left; ?>
													</label> <br /><br />
													<img src="<?php echo $emailtemplate_config['shadow_bottom']['left_thumb']; ?>" alt="" id="thumb-shadow-bottom-left" onclick="image_upload('image-shadow-bottom-left', 'thumb-shadow-bottom-left');" />
													<input type="hidden" name="emailtemplate_config_shadow_bottom[left_img]" value="<?php echo $emailtemplate_config['shadow_bottom']['left_img']; ?>" id="image-shadow-bottom-left"  />
													<br />
				                  					<a onclick="image_upload('image-shadow-bottom-left', 'thumb-shadow-bottom-left');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;
				                  					<a onclick="$('#thumb-shadow-bottom-left').attr('src', '<?php echo $no_image; ?>'); $('#image-shadow-bottom-left').attr('value', '');"><?php echo $text_clear; ?></a>
				                  				</div>
											</td>
											<td>
												<div class="image">
													<label for="image-shadow-bottom-right">
														<?php echo $text_bottom_right; ?>
													</label> <br /><br />
													<img src="<?php echo $emailtemplate_config['shadow_bottom']['right_thumb']; ?>" alt="" id="thumb-shadow-bottom-right" onclick="image_upload('image-shadow-bottom-right', 'thumb-shadow-bottom-right');" />
													<input type="hidden" name="emailtemplate_config_shadow_bottom[right_img]" value="<?php echo $emailtemplate_config['shadow_bottom']['right_img']; ?>" id="image-shadow-bottom-right"  />
													<br />
				                  					<a onclick="image_upload('image-shadow-bottom-right', 'thumb-shadow-bottom-right');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;
				                  					<a onclick="$('#thumb-shadow-bottom-right').attr('src', '<?php echo $no_image; ?>'); $('#image-shadow-bottom-right').attr('value', '');"><?php echo $text_clear; ?></a>
				                  				</div>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</div><!-- editor-shadow -->

	    			<div id="editor-showcase" class="vtabs-content tab-content-editor">
	    				<table class="form">
	    					<tr>
								<td>
									<label for="emailtemplate_config_showcase"><?php echo $entry_showcase; ?></label>
								</td>
								<td>
									<label class="radio">
										<input class="showcase-options" type="radio" name="emailtemplate_config_showcase" value="" <?php if($emailtemplate_config['showcase'] == '') echo ' checked="checked"'; ?>/>
										<b><?php echo $text_none; ?></b>
									</label>
									<label class="radio">
										<input class="showcase-options" type="radio" name="emailtemplate_config_showcase" value="latest" <?php if($emailtemplate_config['showcase'] == 'latest') echo ' checked="checked"'; ?>/>
										<b><?php echo $text_latest; ?></b>
									</label>
									<label class="radio">
										<input class="showcase-options" type="radio" name="emailtemplate_config_showcase" value="bestsellers" <?php if($emailtemplate_config['showcase'] == 'bestsellers') echo ' checked="checked"'; ?>/>
										<b><?php echo $text_bestsellers; ?></b>
									</label>
									<label class="radio">
										<input class="showcase-options" type="radio" name="emailtemplate_config_showcase" value="popular" <?php if($emailtemplate_config['showcase'] == 'popular') echo ' checked="checked"'; ?>/>
										<b><?php echo $text_popular; ?></b>
									</label>
									<label class="radio">
										<input class="showcase-options" type="radio" name="emailtemplate_config_showcase" value="specials" <?php if($emailtemplate_config['showcase'] == 'specials') echo ' checked="checked"'; ?>/>
										<b><?php echo $text_specials; ?></b>
									</label>
									<label class="radio">
										<input class="showcase-options" type="radio" name="emailtemplate_config_showcase" value="products" <?php if($emailtemplate_config['showcase'] == 'products') echo ' checked="checked"'; ?>/>
										<b><?php echo $text_products; ?></b>
									</label>
								</td>
							</tr>
							<tr<?php if($emailtemplate_config['showcase'] != 'products'){ ?> style="display:none;"<?php } ?> class="showcase_products">
								<td>
									<label for="emailtemplate_config_showcase_selection"><?php echo $entry_selection; ?></label>
								</td>
								<td>
									<input class="large" type="text" name="" value="" id="selection" />
									<input type="hidden" name="emailtemplate_config_showcase_selection" value="<?php echo $emailtemplate_config['showcase_selection']; ?>" id="emailtemplate_config_showcase_selection" />

									<ol style="padding-left:20px" class="showcase_selection">
									<?php if(!empty($showcase_selection)): ?>
									<?php foreach($showcase_selection as $row){ ?>
										<li data-id="<?php echo $row['product_id']; ?>"><?php echo $row['name']; ?> <span class="remove"></span></li>
									<?php } ?>
									<?php endif; ?>
									</ol>
								</td>
							</tr>
							<tr>
								<td>
									<label><?php echo $entry_related_products; ?></label>
								</td>
								<td>
									<input type="radio" name="emailtemplate_config_showcase_related" value="1" id="emailtemplate_config_showcase_related" <?php echo ($emailtemplate_config['email_responsive'] == 1) ? ' checked="checked"' : ''; ?>/><?php echo $text_yes; ?>
									<input type="radio" name="emailtemplate_config_showcase_related" value="0" id="emailtemplate_config_showcase_related" <?php echo ($emailtemplate_config['email_responsive'] == 0) ? ' checked="checked"' : ''; ?>/><?php echo $text_no; ?>
									<?php if(isset($error_emailtemplate_config_showcase_related)) { ?><span class="error"><?php echo $error_emailtemplate_config_showcase_related; ?></span><?php } ?>
								</td>
							</tr>
							<tr class="showcase_limit">
								<td>
									<label for="emailtemplate_config_showcase_limit"><?php echo $entry_limit; ?></label>
								</td>
								<td>
									<input class="small" type="text" name="emailtemplate_config_showcase_limit" value="<?php echo $emailtemplate_config['showcase_limit']; ?>" id="emailtemplate_config_showcase_limit" />
								</td>
							</tr>
							<tr>
								<td>
									<label for="emailtemplate_config_showcase_title"><?php echo $entry_title; ?></label>
								</td>
								<td>
									<input class="large" type="text" name="emailtemplate_config_showcase_title" value="<?php echo $emailtemplate_config['showcase_title']; ?>" id="emailtemplate_config_showcase_title" />
								</td>
							</tr>
						</table>
					</div>

	    			<div id="editor-tracking" class="vtabs-content tab-content-editor">
	    				<table class="form">
	    					<tr>
								<td>
									<label><?php echo $entry_tracking; ?></label>
								</td>
								<td>
									<input type="radio" name="emailtemplate_config_tracking" value="1" id="emailtemplate_config_tracking" <?php echo ($emailtemplate_config['tracking'] == 1) ? ' checked="checked"' : ''; ?>/><?php echo $text_yes; ?>
									<input type="radio" name="emailtemplate_config_tracking" value="0" id="emailtemplate_config_tracking" <?php echo ($emailtemplate_config['tracking'] == 0) ? ' checked="checked"' : ''; ?>/><?php echo $text_no; ?>
									<?php if(isset($error_emailtemplate_config_tracking)) { ?><span class="error"><?php echo $error_emailtemplate_config_tracking; ?></span><?php } ?>
								</td>
							</tr>
	    					<tr>
								<td>
									<label for="emailtemplate_config_tracking_campaign_name"><?php echo $entry_tracking_campaign_name; ?></label>
								</td>
								<td>
									<input class="large" type="text" name="emailtemplate_config_tracking_campaign_name" value="<?php echo $emailtemplate_config['tracking_campaign_name']; ?>" id="emailtemplate_config_tracking_campaign_name" />
								</td>
							</tr>
							<tr>
								<td>
									<label for="emailtemplate_config_tracking_campaign_term"><?php echo $entry_tracking_campaign_term; ?></label>
								</td>
								<td>
									<input class="large" type="text" name="emailtemplate_config_tracking_campaign_term" value="<?php echo $emailtemplate_config['tracking_campaign_term']; ?>" id="emailtemplate_config_tracking_campaign_term" />
								</td>
							</tr>
						</table>
					</div>
    			</div><!-- .tabsHolder -->

    			<?php if(!empty($configs)){ ?>
    			<div class="mail content-heading">
    				<h2 class="heading"><?php echo $heading_configs; ?></h2>
    				<div class="buttons"><a href="javascript:void(0)" onclick="$('#form').attr('action', '<?php echo $action; ?>&create_custom=1'); $('#form').submit();" class="button"><span><?php echo $button_create; ?></span></a></div>
    			</div>
				<table class="list">
					<thead>
						<tr>
							<td class="left">
		                		<a href="<?php echo $sort_name; ?>" class="<?php if ($sort == 'name') echo strtolower($order); ?>"><?php echo $column_name; ?></a>
		                	</td>
							<td class="left">
		                		<a href="<?php echo $sort_modified; ?>" class="<?php if ($sort == 'modified') echo strtolower($order); ?>"><?php echo $column_modified; ?></a>
		                	</td>
		              		<td class="left">
								<a href="<?php echo $sort_language; ?>" class="<?php if ($sort == 'language') echo strtolower($order); ?>"><?php echo $column_language; ?></a>
		              		</td>
		              		<td class="left">
								<a href="<?php echo $sort_store; ?>" class="<?php if ($sort == 'store') echo strtolower($order); ?>"><?php echo $column_store; ?></a>
		              		</td>
		              		<td class="left">
								<a href="<?php echo $sort_customer_group; ?>" class="<?php if ($sort == 'customer_group') echo strtolower($order); ?>"><?php echo $column_customer_group; ?></a>
		              		</td>
							<td class="right"><?php echo $column_action; ?></td>
		            	</tr>
		          	</thead>
		          	<tbody>
		            <?php foreach ($configs as $row) { ?>
		            	<tr>
		              		<td class="left"><?php echo $row['name']; ?></td>
		              		<td class="left"><?php echo $row['modified']; ?></td>
		              		<td class="left"><?php echo isset($row['language']) ? $row['language']['name'] : ''; ?></td>
		              		<td class="left"><?php echo isset($row['store']) ? $row['store']['store_name'] : ''; ?></td>
		              		<td class="left"><?php echo isset($row['customer_group']) ? $row['customer_group']['name'] : ''; ?></td>
		              		<td class="right">
		              			<?php if($row['action']){ ?><a href="<?php echo $row['action']; ?>" class="action-icon-edit action-icons" title="<?php echo $text_edit; ?>"><?php echo $text_edit; ?></a><?php } ?>
		              			<?php if(isset($row['action_delete'])){ ?><a href="<?php echo $row['action_delete']; ?>" class="action-icon-delete action-icons" title="<?php echo $text_edit; ?>" onclick="return confirm('<?php echo sprintf($text_delete_confirm, $row['name']); ?>')"><?php echo $text_edit; ?></a><?php } ?>
	              			</td>
		            	</tr>
		            <?php } ?>
		          	</tbody>
		        </table>
		    	<?php } ?>

		    	<?php if(isset($preview_order_id)){ ?>
				<div class="preview-email">
					<div class="preview content-heading">
						<span class="heading"><?php echo $heading_preview; ?></span> <span id="reload_preview" style="display:inline-block; border-bottom:1px dashed black; cursor:pointer;">перегрузить превью с кодом: <input type="text" id="keycode" value="order.admin" /></span>

						<div class="mediaIcons buttons">
							<span class="desktop selected"></span>
							<span class="tablet"></span>
							<span class="mobile"></span>
						</div>
					</div>
	    			<div class="tabsHolder tabsHolder-preview">
		    			<div id="preview" class="vtabs">
		    				<a href="#preview-with"><?php echo $text_withimages; ?></a>
		    				<a href="#preview-without"><?php echo $text_withoutimages; ?></a>
		    				<a href="#test-send"><?php echo $text_test_send; ?></a>
		    			</div>

		    			<div id="preview-with" class="vtabs-content preview-frame" style="padding: 10px 0">
		    				<iframe id="preview-frame" style="width:100%; height:500px; border:none; margin:0 auto; float:none; display:block"></iframe>
		    			</div>

		    			<div id="preview-without" class="removeImages vtabs-content" style="padding: 10px 0">
		    				<iframe id="preview-without-frame" style="width:100%; height:500px; border:none; margin:0 auto; float:none; display:block"></iframe>
		    			</div>

		    			<div id="test-send" class="removeImages vtabs-content">
		    				<table class="form">
		    					<tr class="noBorder">
									<td>
										<label for="send_to"><?php echo $entry_email_address; ?></label>
									</td>
									<td>
										<input class="large" type="text" name="send_to" id="send_to" value="" />

										<div class="buttons" style="float: none; display: inline-block;">
											<a onclick="$('#form').attr('action', '<?php echo $action; ?>'); $('#form').submit();" class="button"><span><?php echo $button_send; ?></span></a>
										</div>
									</td>
								</tr>
							</table>
		    			</div>
	    			</div>
    			</div>
    			<?php } else { ?>
    				<div class="attention"><?php echo $error_preview; ?></div>
    			<?php } ?>

    		</form>
		</div><!-- .content -->
	</div><!-- .box -->
</div>

<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="view/javascript/jquery/colorpicker.js"></script>
<script type="text/javascript"><!--
var ckeditor_config = {
	filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
};
var ckeditor_config_basic = {
	filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	toolbar : [
 		{ name: 'basicstyles', items : [ 'Bold','Italic','Strike','-','RemoveFormat' ] },
 		{ name: 'links', items : [ 'Link','Unlink','Anchor' ] },
 		{ name: 'document', items : [ 'Source'] }
 	],
 	height: 100
};
CKEDITOR.replace('emailtemplate_config_head_text', ckeditor_config_basic);

CKEDITOR.replace('emailtemplate_config_view_browser_text', ckeditor_config_basic);

CKEDITOR.replace('emailtemplate_config_page_footer_text', ckeditor_config);

CKEDITOR.replace('emailtemplate_config_footer_text', ckeditor_config);

CKEDITOR.replace('emailtemplate_config_invoice_header', ckeditor_config);

CKEDITOR.replace('emailtemplate_config_invoice_footer', ckeditor_config);

CKEDITOR.on('instanceReady', function (ev) {
	ev.editor.dataProcessor.htmlFilter.addRules({
        elements: {
            $: function (element) {
                // Output dimensions of images as width and height
                if (element.name == 'img') {
                    var style = element.attributes.style;

                    if (style) {
                        // Get the width from the style.
                        var match = /(?:^|\s)width\s*:\s*(\d+)px/i.exec(style),
                            width = match && match[1];

                        // Get the height from the style.
                        match = /(?:^|\s)height\s*:\s*(\d+)px/i.exec(style);
                        var height = match && match[1];

                        if (width) {
                            element.attributes.style = element.attributes.style.replace(/(?:^|\s)width\s*:\s*(\d+)px;?/i, '');
                            element.attributes.width = width;
                        }

                        if (height) {
                            element.attributes.style = element.attributes.style.replace(/(?:^|\s)height\s*:\s*(\d+)px;?/i, '');
                            element.attributes.height = height;
                        }
                    }
                }

                if (!element.attributes.style) delete element.attributes.style;

                return element;
            }
        }
    });
});

//Image Uploads
function image_upload(field, thumb) {
	$('#dialog').remove();

	$('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?route=common/filemanager&token=<?php echo $token; ?>&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');

	$('#dialog').dialog({
		title: '<?php echo $text_image_manager; ?>',
		close: function (event, ui) {
			if ($('#' + field).attr('value')) {
				$.ajax({
					url: 'index.php?route=common/filemanager/image&token=<?php echo $token; ?>&image=' + encodeURIComponent($('#' + field).attr('value')),
					dataType: 'text',
					success: function(data) {
						$('#' + thumb).replaceWith('<img src="' + data + '" alt="" id="' + thumb + '" />');
					}
				});
			}
		},
		bgiframe: false,
		width: 700,
		height: 400,
		resizable: false,
		modal: false
	});
};

(function($){
	$(document).ready(function(){

		$('#editor a').tabs();
		$('#preview a').tabs();

		// showcase Area autocomplete
		$('#selection').autocomplete({
			delay: 500,
			multiple: true,
			source: function(request, response) {
				$.ajax({
					url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
					dataType: 'json',
					success: function(json) {
						response($.map(json, function(item) {
							return {
								label: item.name,
								value: item.product_id
							}
						}));
					}
				});
			},
			select: function(event, ui) {
				var $field = $('input[name=\'emailtemplate_config_showcase_selection\']');
				var $output = $field.parents('table').find('.showcase_selection');

				if($field.val() == '') {
					$field.val(ui.item.value);
					$output.append('<li data-id="'+ui.item.value+'">'+ui.item.label+'<span class="remove"></span></li>');
					$(this).val('');
				} else {
					var selection = $field.val().split(',');
					if($.inArray(ui.item.value, selection) == -1){
						selection.push(ui.item.value);
						$field.val(selection.join(','));
						$output.append('<li data-id="'+ui.item.value+'">'+ui.item.label+'<span class="remove"></span></li>');
						$(this).val('');
					}
				}

				$output.show();

				return false;
			},
			focus: function(event, ui) {
		      	return false;
		   	}
		});

		// showcase radio option
		$('.showcase-options').change(function(){
			var $table = $(this).parents('table');

			switch($(this).val()){
				case 'products':
					$table.find('.showcase_products').show();
		  		break;
				default:
					$('#emailtemplate_config_showcase_selection').val('');

					$table.find('.showcase_selection').empty('');
					$table.find('.showcase_products').hide();
			}
		});

		// Product Remove
		var productRemoveAction = function(e){
			var $item = $(this).parents('li');
			var id = $item.data('id');
			var $field = $(this).parents('tr').find('input[type=hidden]');
			var values = $.map($field.val().split(','), function(value){ return parseInt(value, 10) });
			var index = $.inArray(id, values);
			if(index !== -1){
				values.splice(index, 1);
			}
			$field.val(values.join(','));
			$item.remove();
		};
		if($.fn.on) {
			$(document).on('click', '.remove', productRemoveAction);
		} else {
			$(document).live('click', '.remove', productRemoveAction);
		}

		$.fn.hasAttr = function(name) {
		   return this.attr(name) !== undefined;
		};

		//Color Pickers
		if($.fn.ColorPicker){
			$("input.fieldColorPicker").each(function(){
				var tis = $(this);

			    tis.ColorPicker({
					onSubmit: function(hsb, hex, rgb, el) {
						$(el).val("#"+hex).next().css("background-color","#"+hex);
						$(el).ColorPickerHide();
					},
					onChange: function(hsb, hex, rgb) {
						tis.val("#"+hex).next().css("background-color","#"+hex);
					},
					onBeforeShow: function() {
						tis.ColorPickerSetColor(tis.val());
					}
				}).bind('keyup', function(){
					tis.ColorPickerSetColor("#"+tis.val());
				});

			    tis.next('.colorPickerPreview').click(function(){
			    	tis.ColorPickerShow();
				});
			});
		}

		//Media Icons
		var $mediaIcons = $(".mediaIcons").children("span");
		var mediaAction = function(){
			var $previewArea = $(this).parents('.preview-email').find('iframe');
			$mediaIcons.removeClass('selected');
			$(this).addClass('selected');

			if($(this).hasClass('desktop')){
				$previewArea.css('width', '100%');
			} else if($(this).hasClass('tablet')){
				$previewArea.css('width', '768px');
			} else if($(this).hasClass('mobile')){
				$previewArea.css('width', '320px');
			}
		};
		$mediaIcons.click(mediaAction);

		<?php if(isset($preview_order_id)){ ?>
		function reload_preview(){
		var keycode = $('#keycode').val();
		$.ajax({
			url: 'index.php?route=module/emailtemplate/preview_email&token=<?php echo $token; ?>&key='+keycode+'&order_id=<?php echo $preview_order_id; ?>',
			dataType: 'text',
			success: function(data) {
				if(data){
					var iframe = document.getElementById('preview-frame');
					iframe.parentNode.style.display = "block";
					iframe = (iframe.contentWindow) ? iframe.contentWindow : (iframe.contentDocument.document) ? iframe.contentDocument.document : iframe.contentDocument;
					iframe.document.open();
					iframe.document.write(data);
					iframe.document.close();

					var iframe = document.getElementById('preview-without-frame');
					var iframeWindow = (iframe.contentWindow) ? iframe.contentWindow : (iframe.contentDocument.document) ? iframe.contentDocument.document : iframe.contentDocument;
					iframeWindow.document.open();
					iframeWindow.document.write(data);
					iframeWindow.document.close();

					$iframe = $(iframe).contents().find("html");
					$iframe.find("img").removeAttr("src");
					$iframe.find("table,td,div").css("backgroundImage", "").removeAttr("background");

					$mediaIcons.eq(0).click();
				}
			}
		});			
		}
		
		$(document).ready(
			function(){		
				reload_preview();
			}
		);
		
		$('#reload_preview').click(function(){reload_preview();});
		<?php } ?>

		// Select first tab if errors are hidden
		var $hidden_error = $('.tabsHolder .error').eq(0);
		if($hidden_error.length > 0){
			// tabs editor
		    $('.tab-nav-editor a[href=#'+$hidden_error.parents(".tab-content-editor").eq(0).attr('id')+']').click();
		}

		//Save Form [ctrl+s]
		$(window).keypress(function(event) {
			if((event.which == 115 && (event.ctrlKey||event.metaKey)) || (event.which == 19)){
			    var $button = $("#submitButton").eq(0);
			    if($button.length){
				    $button.click();
				    event.preventDefault();
			    }
			    return false;
			}
		});
	});
})(jQuery);
//--></script>
<?php echo $footer; ?>